if (window.jQuery)
{
	$('#debug .header').click(function()
	{
		$(this).next().slideToggle();
	});
}