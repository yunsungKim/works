/***
@title:
Vibrate

@version:
2.0

@author:
Andreas Lagerkvist

@date:
2008-08-31

@url:
http://andreaslagerkvist.com/jquery/vibrate/

@license:
http://creativecommons.org/licenses/by/3.0/

@copyright:
2008 Andreas Lagerkvist (andreaslagerkvist.com)

@requires:
jquery

@does:
This plug-in makes any element you want "vibrate" every now and then. Can be used in conjunction with blink for maximum annoyance!

@howto:
jQuery('#ad-area').vibrate(); would make #ad-area vibrate every now and then, options are available, please check the source.

Vibrate currently only works with elements positioned 'static'.

@exampleHTML:
I should vibrate every now and then

@exampleJS:
jQuery('#jquery-vibrate-example').vibrate();
***/
jQuery.fn.vibrate = function (conf) {
	var config = jQuery.extend({
		speed:		10, 
		duration:	0, 
		frequency:	1000, 
		spread:		6
	}, conf);

	return this.each(function () {
		var t = jQuery(this);

		var vibrate = function () {
			var topPos	= Math.floor(Math.random() * config.spread) - ((config.spread - 1) / 2);
			var leftPos	= Math.floor(Math.random() * config.spread) - ((config.spread - 1) / 2);
			var rotate	= Math.floor(Math.random() * config.spread) - ((config.spread - 1) / 2);

			t.css({
				position:			'relative', 
				left:				leftPos + 'px', 
				top:				topPos + 'px', 
				WebkitTransform:	'rotate(' + rotate + 'deg)'  // cheers to erik@birdy.nu for the rotation-idea
			});
		};

		var doVibration = function () {
			var vibrationInterval = setInterval(vibrate, config.speed);

			var stopVibration = function () {
				clearInterval(vibrationInterval);
				t.css({
					position:			'static', 
					WebkitTransform:	'rotate(0deg)'
				});
			};

			setTimeout(stopVibration, config.duration);
		};

		setInterval(doVibration, config.frequency);
	});
};