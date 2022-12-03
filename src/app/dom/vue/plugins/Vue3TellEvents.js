/**
 * Heavily inspired and derived from the vue3-touch-events library.
 * ---
 * @link    https://github.com/robinrodricks/vue3-touch-events
 * @author  [inspire] Robin Rodricks, Xavier Julien, Jerry Bendy
 * @author  [deriver] Tell Konkle
 */

function clientX(ev) {
    return ev.type.indexOf('mouse') >= 0
        ? ev.clientX
        : ev.touches[0].clientX;
};

function clientY(ev) {
    return ev.type.indexOf('mouse') >= 0
        ? ev.clientY
        : ev.touches[0].clientY;
};

const Vue3TellEvents = {

    install : function(app, cfg) {

        cfg = Object.assign({}, {
            disableClick          : false,
            dragFrequency         : 50,
            driftTolerance        : 30,
            longTapTimeInterval   : 400,
            rollOverFrequency     : 100,
            swipeTolerance        : 30,
            tapTolerance          : 10,
            touchAgainTolerance   : 350,
            touchClass            : '',
            touchHoldTolerance    : 400,
            windowResizeFrequency : 50,
        }, cfg);

        function touchStart(ev) {
            const
                el      = this,
                tell    = el.tell,
                cfg     = tell.cfg,
                isTouch = ev.type.indexOf('touch') >= 0,
                isMouse = ev.type.indexOf('mouse') >= 0;

            if (isTouch) {
                tell.lastTouchStartTime = ev.timeStamp;
            }

            if (isMouse && tell.lastTouchStartTime
               && (ev.timeStamp - tell.lastTouchStartTime) < cfg.touchAgainTolerance
            ) {
                return;
            }

            if (tell.touchStarted) {
                return;
            }

            addTouchClass(el);

            tell.touchStarted    = true;
            tell.touchMoved      = false;
            tell.swipeOutBounded = false;
            tell.startX          = clientX(ev);
            tell.startY          = clientY(ev);
            tell.currentX        = 0;
            tell.currentY        = 0;
            tell.touchStartTime  = ev.timeStamp;
            tell.hasSlide        = hasEv(el, 'slide')
                                || hasEv(el, 'slide.left')
                                || hasEv(el, 'slide.right')
                                || hasEv(el, 'slide.top')
                                || hasEv(el, 'slide.bottom');
            tell.hasSwipe        = hasEv(el, 'swipe')
                                || hasEv(el, 'swipe.left')
                                || hasEv(el, 'swipe.right')
                                || hasEv(el, 'swipe.top')
                                || hasEv(el, 'swipe.bottom');

            if (hasEv(el, 'hold')) {
                tell.touchHoldTimer = setTimeout(function() {
                    tell.touchHoldTimer = null;
                    triggerEv(ev, el, 'hold');
                }, 400);
            }

            triggerEv(ev, el, 'press');
        };

        function touchMove(ev) {
            const
                el     = this,
                tell   = el.tell,
                cfg    = tell.cfg,
                curX   = clientX(ev),
                curY   = clientY(ev),
                moving = (curX !== tell.currentX) || (curY !== tell.currentY);

            tell.currentX = curX;
            tell.currentY = curY;

            if ( ! tell.touchMoved) {
                tell.touchMoved = Math.abs(tell.startX - curX) > cfg.tapTolerance ||
                                  Math.abs(tell.startY - curY) > cfg.tapTolerance;

                if (tell.touchMoved) {
                    cancelTouchHoldTimer(tell);
                    triggerEv(ev, el, 'drag.once');
                }
            } else if (tell.hasSwipe && ! tell.swipeOutBounded) {
                tell.swipeOutBounded = Math.abs(tell.startX - curX) > cfg.swipeTolerance &&
                                       Math.abs(tell.startY - curY) > cfg.swipeTolerance;
            }

            if (hasEv(el, 'rollover') && moving) {
                if ( ! tell.touchRollTime || ev.timeStamp > (tell.touchRollTime + cfg.rollOverFrequency)) {
                    tell.touchRollTime = ev.timeStamp;
                    triggerEv(ev, el, 'rollover');
                }
            }

            if (hasEv(el, 'drag') && tell.touchStarted && tell.touchMoved && moving) {
                if ( ! tell.touchDragTime || ev.timeStamp > (tell.touchDragTime + cfg.dragFrequency)) {
                    tell.touchDragTime = ev.timeStamp;
                    triggerEv(ev, el, 'drag');
                }
            }
        };

        function touchCancel() {
            const tell = this.tell;

            cancelTouchHoldTimer(tell);

            tell.touchStarted = false;
            tell.touchMoved   = false;
            tell.startX       = 0;
            tell.startY       = 0;
        };

        function touchEnd(ev) {
            const
                el      = this,
                tell    = el.tell,
                cfg     = tell.cfg,
                isTouch = ev.type.indexOf('touch') >= 0,
                isMouse = ev.type.indexOf('mouse') >= 0;

            if (isTouch) {
                tell.lastTouchEndTime = ev.timeStamp;
            }

            cancelTouchHoldTimer(tell);

            removeTouchClass(el);

            tell.touchStarted = false;

            if (isMouse && tell.lastTouchEndTime
                && (ev.timeStamp - tell.lastTouchEndTime) < cfg.touchAgainTolerance
            ) {
                return;
            }

            triggerEv(ev, el, 'release');

            if ( ! tell.touchMoved) {
                if (hasEv(el, 'longtap') && ev.timeStamp - tell.touchStartTime > cfg.longTapTimeInterval) {
                    if (ev.cancelable) {
                        ev.preventDefault();
                    }

                    triggerEv(ev, el, 'longtap');
                } else if (hasEv(el, 'hold') && isTouch && ! tell.touchHoldTimer) {
                    if (ev.cancelable) {
                        ev.preventDefault();
                    }

                    return;
                } else {
                    triggerEv(ev, el, 'tap');
                }

                return;
            }

            if (tell.hasSlide) {
                let direction,
                    driftX = tell.startX - tell.currentX,
                    driftY = tell.startY - tell.currentY;

                if (driftY >= cfg.driftTolerance && Math.abs(driftY) > Math.abs(driftX)) {
                    direction = 'top';
                } else if (driftX <= -cfg.driftTolerance && Math.abs(driftY) <= Math.abs(driftX)) {
                    direction = 'right';
                } else if (driftY <= -cfg.driftTolerance && Math.abs(driftY) > Math.abs(driftX)) {
                    direction = 'bottom';
                } else if (driftX >= cfg.driftTolerance && Math.abs(driftY) <= Math.abs(driftX)) {
                    direction = 'left';
                }

                if (direction && hasEv(el, 'slide.' + direction)) {
                    triggerEv(ev, el, 'slide.' + direction, direction);
                } else if (direction) {
                    triggerEv(ev, el, 'slide', direction);
                }
            }

            if (tell.hasSwipe && ! tell.swipeOutBounded) {
                let direction,
                    distanceY = Math.abs(tell.startY - tell.currentY),
                    distanceX = Math.abs(tell.startX - tell.currentX);

                if (distanceY > cfg.swipeTolerance || distanceX > cfg.swipeTolerance) {
                    if (distanceY > cfg.swipeTolerance) {
                        direction = tell.startY > tell.currentY ? 'top' : 'bottom';
                    } else {
                        direction = tell.startX > tell.currentX ? 'left' : 'right';
                    }

                    if (hasEv(el, 'swipe.' + direction)) {
                        triggerEv(ev, el, 'swipe.' + direction, direction);
                    } else {
                        triggerEv(ev, el, 'swipe', direction);
                    }
                }
            }
        };

        function mouseEnter(ev) {
            addTouchClass(this);
        };

        function mouseLeave(ev) {
            removeTouchClass(this);
        };

        function windowResizeAll(ev) {
            const els = window.Vue3TellWindowResize;

            for (let i = 0; i < els.length; i++) {
                if (els[i].tell) {
                    els[i].dispatchEvent(new CustomEvent('tellwindowresize', { detail : ev }));
                }
            }
        };

        function windowResize(ev) {
            const
                el   = this,
                tell = el.tell,
                cfg  = tell.cfg;

            if ( ! hasEv(el, 'window.resize')) {
                return;
            }

            if (tell.windowResizeTimer) {
                clearTimeout(tell.windowResizeTimer);
            }

            tell.windowResizeTimer = setTimeout(() => {
                triggerEv(ev.detail, el, 'window.resize');
            }, cfg.windowResizeFrequency);
        };

        function hasEv(el, type) {
            return el.tell.registry[type].length > 0;
        };

        function triggerEv(ev, el, type, param) {
            const
                tell      = el.tell,
                callbacks = tell.registry[type];

            for (let i = 0; i < callbacks.length; i++) {
                let binding = callbacks[i];

                if (binding.modifiers.stop) {
                    ev.stopPropagation();
                }

                if (binding.modifiers.prevent) {
                    ev.preventDefault();
                }

                if (binding.modifiers.self && ev.target !== ev.currentTarget) {
                    continue;
                }

                if ('function' === typeof binding.value) {
                    if (param) {
                        binding.value(param, ev);
                    } else {
                        binding.value(ev);
                    }
                }
            }
        };

        function addTouchClass(el) {
            const className = el.tell.cfg.touchClass;
            className && el.classList.add(className);
        };

        function removeTouchClass(el) {
            const className = el.tell.cfg.touchClass;
            className && el.classList.remove(className);
        };

        function cancelTouchHoldTimer(tell) {
            if (tell.touchHoldTimer) {
                clearTimeout(tell.touchHoldTimer);
                tell.touchHoldTimer = null;
            }
        };

        function buildTell(el, customCfg = {}) {
            el.tell = el.tell || {
                registry : {
                    'drag'          : [],
                    'drag.once'     : [],
                    'hold'          : [],
                    'longtap'       : [],
                    'press'         : [],
                    'release'       : [],
                    'rollover'      : [],
                    'slide'         : [],
                    'slide.bottom'  : [],
                    'slide.left'    : [],
                    'slide.right'   : [],
                    'slide.top'     : [],
                    'swipe'         : [],
                    'swipe.bottom'  : [],
                    'swipe.left'    : [],
                    'swipe.right'   : [],
                    'swipe.top'     : [],
                    'tap'           : [],
                    'window.resize' : [],
                },
                cfg                : cfg,
                hasEvents          : false,
                touchDragTime      : null,
                touchRollTime      : null,
                touchHoldTimer     : null,
                touchStartTime     : null,
                touchStarted       : false,
                touchMoved         : false,
                lastTouchStartTime : null,
                lastTouchEndTime   : null,
                windowResizeTimer  : null,
                swipeOutBounded    : false,
                hasSlide           : false,
                hasSwipe           : false,
                startX             : 0,
                startY             : 0,
                currentX           : 0,
                currentY           : 0,
            };

            if (customCfg) {
                el.tell.cfg = Object.assign({}, el.tell.cfg, customCfg);
            }

            return el.tell;
        };

        app.directive('tell', {
            beforeMount : (el, binding) => {
                const
                    tell = buildTell(el),
                    type = binding.arg,
                    mod  = binding.modifiers;

                if ( ! tell.hasEvents) {
                    el.addEventListener('touchstart', touchStart, { passive: true });
                    el.addEventListener('touchmove', touchMove, { passive: true });
                    el.addEventListener('touchcancel', touchCancel);
                    el.addEventListener('touchend', touchEnd);
                    el.addEventListener('mousedown', touchStart);
                    el.addEventListener('mousemove', touchMove);
                    el.addEventListener('mouseup', touchEnd);
                    el.addEventListener('mouseenter', mouseEnter);
                    el.addEventListener('mouseleave', mouseLeave);
                    el.addEventListener('tellwindowresize', windowResize);
                    tell.hasEvents = true;
                }

                if ( ! window.Vue3TellWindowResize) {
                    window.addEventListener('resize', windowResizeAll);
                    window.Vue3TellWindowResize = [];
                }

                switch (type) {
                    case 'slide':
                    case 'swipe':
                        if (Object.keys(mod).length > 0) {
                            for (let m in mod) {
                                if (['top', 'right', 'bottom', 'left'].indexOf(m) >= 0) {
                                    tell.registry[type + '.' + m].push(binding);
                                } else {
                                    console.error('Unknown v-tell event type:', type + '.' + m);
                                }
                            }
                        } else {
                            tell.registry[type].push(binding);
                        }

                        break;
                    case 'drag':
                    case 'hold':
                    case 'longtap':
                    case 'press':
                    case 'release':
                    case 'rollover':
                    case 'tap':
                        tell.registry[type].push(binding);
                        break;
                    case 'window':
                        if (Object.keys(mod).length > 0) {
                            for (let m in mod) {
                                if (['resize'].indexOf(m) >= 0) {
                                    tell.registry[type + '.' + m].push(binding);
                                    window.Vue3TellWindowResize.push(el);
                                } else {
                                    console.error('Unknown v-tell event type:', type + '.' + m);
                                }
                            }
                        }

                        break;
                    default:
                        console.error('Unknown v-tell event type:', type);
                    break;
                };
            },
            unmounted : (el) => {
                el.removeEventListener('touchstart', touchStart);
                el.removeEventListener('touchmove', touchMove);
                el.removeEventListener('touchcancel', touchCancel);
                el.removeEventListener('touchend', touchEnd);
                el.removeEventListener('mousedown', touchStart);
                el.removeEventListener('mousemove', touchMove);
                el.removeEventListener('mouseup', touchEnd);
                el.removeEventListener('mouseenter', mouseEnter);
                el.removeEventListener('mouseleave', mouseLeave);
                el.removeEventListener('tellwindowresize', windowResize);
                delete el.tell;
            },
        });

        app.directive('tell-options', {
            beforeMount: function(el, binding) {
                buildTell(el, binding.value);
            }
        });

    }
};

export { Vue3TellEvents };
