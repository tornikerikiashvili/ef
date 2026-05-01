/**
 * Rotating typewriter effect for elements with class .typewrite,
 * data-type='["phrase 1", ...]' (JSON array) and optional data-period (ms pause when word complete).
 */
(function () {
    function injectCursorStylesOnce() {
        if (document.getElementById('typewrite-styles')) {
            return;
        }
        var style = document.createElement('style');
        style.id = 'typewrite-styles';
        style.textContent =
            '.typewrite.typewrite--blink { position: relative; } ' +
            '.typewrite.typewrite--blink::after { content: ""; display: inline-block; width: 0.08em; height: 0.9em; margin-left: 0.06em; vertical-align: -0.06em; background-color: currentColor; animation: typewrite-caret-opacity 1s step-end infinite; } ' +
            '@keyframes typewrite-caret-opacity { 50% { opacity: 0; } }';
        document.head.appendChild(style);
    }

    function TxtType(el, toRotate, period) {
        this.toRotate = toRotate;
        this.el = el;
        this.loopNum = 0;
        this.period = parseInt(period, 10) || 2000;
        this.txt = '';
        this.isDeleting = false;
        el.classList.add('typewrite--blink');
        this.tick();
    }

    TxtType.prototype.tick = function () {
        var fullTxt = this.toRotate[this.loopNum % this.toRotate.length];

        if (this.isDeleting) {
            this.txt = fullTxt.substring(0, this.txt.length - 1);
        } else {
            this.txt = fullTxt.substring(0, this.txt.length + 1);
        }

        this.el.textContent = this.txt;

        var that = this;
        var delta = 200 - Math.random() * 100;

        if (this.isDeleting) {
            delta /= 2;
        }

        if (!this.isDeleting && this.txt === fullTxt) {
            delta = this.period;
            this.isDeleting = true;
        } else if (this.isDeleting && this.txt === '') {
            this.isDeleting = false;
            this.loopNum++;
            delta = 500;
        }

        setTimeout(function () {
            that.tick();
        }, delta);
    };

    function init() {
        injectCursorStylesOnce();
        var elements = document.getElementsByClassName('typewrite');
        for (var i = 0; i < elements.length; i++) {
            var el = elements[i];
            var raw = el.getAttribute('data-type');
            var period = el.getAttribute('data-period');
            if (!raw) {
                continue;
            }
            try {
                var toRotate = JSON.parse(raw);
                if (Array.isArray(toRotate) && toRotate.length > 0) {
                    new TxtType(el, toRotate, period);
                }
            } catch (e) {
                /* ignore invalid JSON */
            }
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
