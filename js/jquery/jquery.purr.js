/**
 * jquery.purr.js
 * Copyright (c) 2008 Net Perspective (net-perspective.com)
 * Licensed under the MIT License (http://www.opensource.org/licenses/mit-license.php)
 * 
 * @author R.A. Ray
 * @projectDescription	jQuery plugin for dynamically displaying unobtrusive messages in the browser. Mimics the behavior of the MacOS program "Growl."
 * @version 0.1.0
 * 
 * @requires jquery.js (tested with 1.2.6)
 * 
 * @param fadeInSpeed 					int - Duration of fade in animation in miliseconds
 * 													default: 500
 *	@param fadeOutSpeed  				int - Duration of fade out animationin miliseconds
 														default: 500
 *	@param removeTimer  				int - Timeout, in miliseconds, before notice is removed once it is the top non-sticky notice in the list
 														default: 4000
 *	@param isSticky 						bool - Whether the notice should fade out on its own or wait to be manually closed
 														default: false
 *	@param usingTransparentPNG 	bool - Whether or not the notice is using transparent .png images in its styling
 														default: false
 */;(function($){$.purr=function(notice,options){notice=$(notice);if(!options.isSticky){notice.addClass('not-sticky');}var cont=document.getElementById('purr-container');if(!cont){cont='<div id="purr-container"></div>';}cont=$(cont);$('body').append(cont);notify();function notify(){var close=document.createElement('a');$(close).attr({className:'close',href:'#close'}).appendTo(notice).click(function(){removeNotice();return false;});var sticky=document.createElement('a');$(sticky).attr({className:'sticky',href:'#sticky'}).appendTo(notice).click(function(){if($(notice).hasClass('not-sticky')){$(notice).removeClass('not-sticky');clearTimeout($(notice).data('timeout'));clearInterval($(notice).data('interval'));}else
{$(notice).addClass('not-sticky');var obj=$(this);var topSpotInt=setInterval(function(){if(obj.prevAll('.not-sticky').length==0){clearInterval(topSpotInt);clearTimeout(obj.data('timeout'));obj.data('timeout',setTimeout(function(){if($.isFunction(obj.data('fn.removeNotice'))){obj.data('fn.removeNotice')();}},2000));}},200);obj.data('interval',topSpotInt);}return false;});notice.prependTo(cont).hide();if(jQuery.browser.msie&&options.usingTransparentPNG){notice.show();}else
{notice.fadeIn(options.fadeInSpeed);}if(!options.isSticky){var topSpotInt=setInterval(function(){if(notice.prevAll('.not-sticky').length==0){clearInterval(topSpotInt);notice.data('timeout',setTimeout(function(){removeNotice();},options.removeTimer));}},200);notice.data('interval',topSpotInt);}}function removeNotice(){if(jQuery.browser.msie&&options.usingTransparentPNG){notice.css({opacity:0}).animate({height:'0px'},{duration:options.fadeOutSpeed,complete:function(){notice.remove();}});}else
{notice.animate({opacity:'0'},{duration:options.fadeOutSpeed,complete:function(){notice.animate({height:'0px'},{duration:options.fadeOutSpeed,complete:function(){notice.remove();}});}});}};notice.data('fn.removeNotice',removeNotice);};$.fn.purr=function(options){options=options||{};options.fadeInSpeed=options.fadeInSpeed||500;options.fadeOutSpeed=options.fadeOutSpeed||500;options.removeTimer=options.removeTimer||4000;options.isSticky=options.isSticky||false;options.usingTransparentPNG=options.usingTransparentPNG||false;this.each(function(){new $.purr(this,options);});return this;};})(jQuery);