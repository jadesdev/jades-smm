<script type="text/javascript">
    @if (Session::get('success'))
        toastr.success('{{ Session::get('success') }}', 'Successful');
    @endif
    @if (Session::get('error'))
        toastr.error('{{ Session::get('error') }}', 'Error');
    @endif
    @if (count($errors) > 0)
        // console.log('{!! implode('<br>', $errors->all()) !!}');
        toastr.error('{!! implode('<br>', $errors->all()) !!}', 'Error');
    @endif

    function copyFunction(element) {
        var aux = document.createElement("input");
        // Assign it the value of the specified element
        aux.setAttribute("value", element);
        document.body.appendChild(aux);
        aux.select();
        document.execCommand("copy");
        document.body.removeChild(aux);

        toastr.info('Copied Successfully', "Success");
    }
    document.addEventListener('DOMContentLoaded', function() {
        window.addEventListener('alert', event => {
            event.detail.forEach(({
                type,
                message,
                title
            }) => {
                toastr[type](message, title ?? 'Successful');
            });
        });
    });
    document.addEventListener('livewire:navigating', () => {
        JDLoader.open('.loader-mask');

        applyThemeFromLocalStorage();
    })
    document.addEventListener('livewire:navigated', () => {
        JDLoader.close('.loader-mask');
        applyThemeFromLocalStorage();

        updateActiveNavItem();
    })

    function applyThemeFromLocalStorage() {
        const theme = localStorage.getItem('color-theme') ||
            (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');

        const html = document.documentElement;
        const icon = document.getElementById('dark-mode-icon');

        if (theme === 'dark') {
            html.classList.add('dark');
            icon?.classList.replace('fa-moon', 'fa-sun');
        } else {
            html.classList.remove('dark');
            icon?.classList.replace('fa-sun', 'fa-moon');
        }
    }

    function updateActiveNavItem() {
        const currentUrl = window.location.href.split(/[?#]/)[0];
        const navItems = document.querySelectorAll('.nav-menu__item');

        navItems.forEach(item => {
            const link = item.querySelector('a');
            if (!link) return;

            const linkPath = new URL(link.href);
            if (linkPath.href === currentUrl) {
                item.classList.add('activePage');
            } else {
                item.classList.remove('activePage');
            }
        });
    }

    function openLink(url, target = '_self') {
        window.open(url, target);
    }
    document.addEventListener('DOMContentLoaded', applyThemeFromLocalStorage);
</script>
