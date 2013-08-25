# JS and CSS assets library for Kohana

- Compile and serve js, css, and less files
- For Kohana 3.3.x

## Create an assets object

```
$assets = Assets::factory();
```

## Add css or js files to the assets object

Every asset you add has to have a key and a value associated with it. They key is to keep track of all the assets already added, and the value is the path to the asset.

```
// With an array
$assets
	->css(array(
		'base' => 'base.less',
		'section' => 'section.less',
	))
	->js(array(
		'plugins' => 'plugins/plugins.js',
		'section' => 'section.js',
	));

$assets = Assets::factory()
	->css(array('bootstrap.min.css', 'main.css'))
	->js(array('jquery.min.js', 'bootstrap.min.js'));

// You can also add them one at a time
$assets
	->css('base', 'base.less')
	->js('section', 'section.js');

```

## Render assets

To render assets that have been added to the assets object, use `Assets::get()`.

```
<?php echo $assets->get('css') ?>
<?php echo $assets->get('js') ?>
```