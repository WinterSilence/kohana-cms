<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Backend blog category controller
 * 
 */
class Controller_Page_Blog_Category extends Controller_Page
{
	/**
	 * 
	 */
	protected $model = 'Blog_Category';

	/**
	 * View category tree
	 */
	public function action_list()
	{
		$this->content->categories = $this->model->get_descendants(TRUE);
	}
	
	/**
	 * Add node
	 */
	public function action_add()
	{
		$this->content
		$this->action_edit();
	}
	
	/**
	 * Edit node
	 */
	public function action_edit()
	{
		// Check loading
		if ($this->request->action() != 'add' AND ! $this->model->loaded())
		{
			throw HTTP_Exception::factory(404, 'Node not found');
		}
		
		$this->content->category = $this->model;
		$this->content->categories = $this->model->get_descendants(TRUE)->as_array();
		
		$this->template->category   =
			// all root node child's
			'root' => $root,
			'node' => $node,
			'categories' => $root->get_descendants(),
		));
	}

	/**
	 * Create, Update tree node action
	 */
	public function action_save()
	{
		/** @var Model_Category $root **/ // root node
		$root = ORM::factory('Category', Arr::get($this->request->post(), 'parent'));

		// check root
		if ( ! $root->loaded())
		{
			throw new HTTP_Exception_502('Root node of categories tree not founded');
		}

		/** @var Model_Category $node **/ // create new node object
		$node = ORM::factory('Category', Arr::get($this->request->post(), 'id'));

		// bind data
		$node->values($this->request->post(), array('name', 'description'));

		// insert node as last child of root
		try {
			if (! $node->loaded())
			{
				// insert
				$node->insert_as_last_child_of($root);
			}
			else
			{
				// save
				$node->save();

				// change parent if needed
				if (! $root->is_equal_to($node->get_parent()))
				{
					$node->move_as_last_child_of($root);
				}
			}
		} catch (Exception $e) {
			throw $e;
			// process error check
		}

		// setup success message
		Session::instance()->set('message', 'Operation was successfully completed');

		// redirect to modify page
		$this->request->redirect(Route::url('default', array('controller' => 'category', 'action' => 'edit', 'id' => $node->id)));
	}

	/**
	 * Delete node action
	 */
	public function action_delete()
	{
		// Check loading
		if ($this->request->action() != 'delete' AND ! $this->model->loaded())
		{
			throw HTTP_Exception::factory(404, 'Node not found');
		}
		// Remove node
		$model->delete();
		// Redirect to category tree
		$this->redirect($this->request->referrer());
	}

} // End Controller_Page_Blog_Category