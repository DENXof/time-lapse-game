// public/js/admin.js
$(document).ready(function() {
    console.log('Админ-панель инициализирована');

    // 1. Исправляем боковое меню
    initSidebarMenu();

    // 2. Инициализируем Bootstrap компоненты
    initBootstrapComponents();

    // 3. Добавляем дополнительные улучшения
    enhanceAdminUI();
});

function initSidebarMenu() {
    // Делегирование событий для меню
    $(document).on('click', '.admin-sidebar .nav-link', function(e) {
        // Убираем активный класс у всех элементов
        $('.admin-sidebar .nav-link').removeClass('active');

        // Добавляем активный класс текущему
        $(this).addClass('active');

        // Логирование для отладки
        console.log('Переход к:', $(this).attr('href'));

        // Не предотвращаем стандартное поведение
        return true;
    });

    // Автоматически активируем текущую страницу
    highlightCurrentPage();
}

function highlightCurrentPage() {
    const currentUrl = window.location.pathname;

    $('.admin-sidebar .nav-link').each(function() {
        const linkUrl = $(this).attr('href');
        if (linkUrl && currentUrl.includes(linkUrl.replace(window.location.origin, ''))) {
            $(this).addClass('active');
        }
    });
}

function initBootstrapComponents() {
    // Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    });

    // Alerts
    $('.alert').alert();
}

function enhanceAdminUI() {
    // Добавляем плавные переходы
    $('a').on('click', function(e) {
        if (this.hash !== '') {
            e.preventDefault();
            const hash = this.hash;
            $('html, body').animate({
                scrollTop: $(hash).offset().top - 70
            }, 800);
        }
    });

    // Улучшаем таблицы
    $('table').addClass('table table-hover');

    // Добавляем подтверждение для опасных действий
    $('form[data-confirm]').on('submit', function(e) {
        const message = $(this).data('confirm') || 'Вы уверены?';
        if (!confirm(message)) {
            e.preventDefault();
        }
    });
}
