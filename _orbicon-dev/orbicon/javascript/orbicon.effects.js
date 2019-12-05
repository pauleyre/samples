// YUI effects

// yellow fade
function yfade(el)
{
	var restorebg = function() {
		var el = this.getEl();
		YAHOO.util.Dom.setStyle(el, 'backgroundColor', bgcolor);
	}

	var bgcolor = YAHOO.util.Dom.getStyle(el, 'backgroundColor');
	bgcolor = (empty(bgcolor)) ? 'none' : bgcolor;

	var yellow_fade = new YAHOO.util.ColorAnim(el, { backgroundColor: { from: '#ffff00', to: '#ffffff' } }, 1, YAHOO.util.Easing.easeOut);
	yellow_fade.onComplete.subscribe(restorebg);
	yellow_fade.animate();
}

/*function effect_grow(el)
{
	var attributes = {
	   width: { from: 1, to: 100, unit: '%' },
	   height: { from: 1, to: 100, unit: '%' }
	};

	var grow = new YAHOO.util.Anim(el, attributes);
	grow.onStart.subscribe(function () { $(el).style.display = 'block'; });
	grow.animate();
}

function effect_shrink(el)
{
	var attributes = {
	   width: { from: 100, to: 1, unit: '%' },
	   height: { from: 100, to: 1, unit: '%' }
	};

	var shrink = new YAHOO.util.Anim(el, attributes, 0.5);
	shrink.onComplete.subscribe(function () { $(el).style.display = 'none'; });
	shrink.animate();
}*/