// JD Preloader
if (typeof window.JDLoader === 'undefined') {
    window.JDLoader = {
        open: function(selector) {
            if (!selector) {
                const defaultLoader = document.getElementById('loader-container');
                if (defaultLoader) {
                    defaultLoader.style.display = 'flex';
                }
                return;
            }

            if (selector.startsWith('.')) {
                const elements = document.querySelectorAll(selector);
                elements.forEach(element => {
                    element.style.display = 'flex';
                });
            } else if (selector.startsWith('#')) {
                const element = document.querySelector(selector);
                if (element) {
                    element.style.display = 'flex';
                }
            } else {
                const element = document.getElementById(selector);
                if (element) {
                    element.style.display = 'flex';
                }
            }
        },

        close: function(selector) {
            if (!selector) {
                const defaultLoader = document.getElementById('loader-container');
                if (defaultLoader) {
                    defaultLoader.style.display = 'none';
                }
                return;
            }

            if (selector.startsWith('.')) {
                const elements = document.querySelectorAll(selector);
                elements.forEach(element => {
                    element.style.display = 'none';
                });
            } else if (selector.startsWith('#')) {
                const element = document.querySelector(selector);
                if (element) {
                    element.style.display = 'none';
                }
            } else {
                const element = document.getElementById(selector);
                if (element) {
                    element.style.display = 'none';
                }
            }
        }
    };
}