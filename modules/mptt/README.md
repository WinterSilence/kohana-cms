# This is the stable branch for Kohana 3.1 and is no longer maintained!!!

# MPTT Library, extends ORM

## Setup

Place module in /modules/ and include the call in your bootstrap.

## Declaring your ORM object

<pre>
class Model_Category extends ORM_MPTT{}
</pre>

## Usage Examples

### Creating a root node:
<pre>
$cat = ORM::factory('Category_Mptt');
$cat->name = 'Music';
$cat->insert_as_new_root();
echo 'Category ID'.$mptt->id.' set at level '.$cat->lvl.' (scope: '.$cat->scope.')';
$c1 = $cat; // Saving id for next example
</pre>

### Creating a child node:
<pre>
$cat->clear(); // Clearing ORM object
$cat->name = 'Terminology';
$cat->insert_as_last_child($c1);
</pre>

Нам потребуется таблица БД, вот ее упрощенная структура:

id – уникальный идентификатор-счетчик
parent_id – родительский id (для рутов – значение = 0)
lvl – уровень (для рутовых категорий он равен 1)
lft
rgt
scope – № ветви
name – наименование категории
url – url категории

Модель наследуем от ORM_MPTT
class Model_Category extends ORM_MPTT{}

Создание корневого узла:
<pre>
$cat = ORM::factory('category');
$cat->name = 'Каталог';
$cat->insert_as_new_root();
</pre>
Создание последнего дочернего узла:
<pre>
$parent_cat = ORM::factory('category')->where('name', '=', 'Каталог')->find();
$cat->name = 'Мобильные телефоны';
$cat->insert_as_last_child($parent_cat);
$cat->name = 'Планшеты';
$cat->insert_as_last_child($parent_cat);
$cat->name = 'Аксессуары для планшетов';
$cat->insert_as_last_child($parent_cat);
$cat->name = 'Литература о планшетах';
$cat->insert_as_last_child($parent_cat);
</pre>
По аналогии создается дочерний узел, но вставляется перед всеми существующими:

$parent_cat = ORM::factory('category')->where('name', '=', 'Каталог')->find();
 
$cat->name = 'Бытовая техника';
 
$cat->insert_as_first_child($parent_cat);

Создание братского узла (узла того же уровня вложенности) перед указанным узлом:

$sibling_cat = ORM::factory('category')->where('name', '=', 'Планшеты')->find();
 
$cat->name = 'Комплектующие';
 
$cat->insert_as_prev_sibling($sibling_cat);

Создание братского узла (узла того же уровня вложенности) после указанного узла:

$sibling_cat = ORM::factory('category')->where('name', '=', 'Планшеты')->find();
$cat->name = 'Ноутбуки';
$cat->insert_as_next_sibling($sibling_cat);

Перемещение произвольного узла с подузлами в выбранный узел (в нашем случае перемещаем категорию "Аксессуары для планшетов" в категорию "Планшеты" перед всеми имеющимися узлами):

$source_cat = ORM::factory('category')
->where('name', '=', 'Аксессуары для планшетов')->find();
 
$destination_cat = ORM::factory('category')
->where('name', '=', 'Планшеты')->find();
 
$cat->move_to_first_child($destination_cat);
//Перемещаем на место первого дочернего узла

То же самое, но теперь вставляем категорию после всех имеющихся узлов:

$source_cat = ORM::factory('category')
->where('name', '=', 'Литература о планшетах')->find();
 
$destination_cat = ORM::factory('category')
->where('name', '=', 'Планшеты')->find();
 
$cat->move_to_last_child($destination_cat);
//Вставляем последним дочерним узлом

По аналогии возможны перемещения братских узлов:

$sibling_cat = ORM::factory('category')
->where('name', '=', 'Мобильные телефоны')->find();
 
$cat = ORM::factory('category')
->where('name', '=', 'Планшеты')->find();
 
$cat->move_to_prev_sibling($sibling_cat);
//Перемещаем на позицию перед братским узлом

$sibling_cat = ORM::factory('category')
->where('name', '=', 'Мобильные телефоны')->find();
 
$cat = ORM::factory('category')
->where('name', '=', 'Планшеты')->find();
 
$cat->move_to_next_sibling($sibling_cat);
//Перемещаем на позицию после братского узла

В этом месте лично меня ожидал неприятный сюрприз.
Никаким образом категории не желали перемещаться.
Для решения данной проблемы необходимо изменить код в модуле modules\orm-mptt\classes\kohana\orm\mptt.php в методе lock(), на следующий:
<pre>
protected function lock()
{
    $q = 'LOCK TABLE '.$this->_db->quote_table($this->_table_name).' WRITE';
 
    if ($this->_object_name)
    {
        $q.= ', '.$this->_db->quote_table($this->_table_name);
        $q.= ' AS '.$this->_db->quote_column($this->_object_name).' WRITE';
    }
 
    $this->_db->query(NULL, $q, TRUE);
}
</pre>
Перемещение узлов без ошибок возможно только в пределах одного корневого узла, перемещение узлов между деревьями необходимо реализовывать путем удаления узла из одного дерева и создания нового узла в другом дереве.
