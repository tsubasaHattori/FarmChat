$(function () {
    $('#toggle-sidebar').on('click', function () {
        $('#sidebar').toggleClass('sidebar-open');
        $('#sidebar-overlay').toggleClass('sidebar-overlay-open');
    })

    $('#sidebar-overlay').on('click', function () {
        $('#sidebar').toggleClass('sidebar-open');
        $('#sidebar-overlay').toggleClass('sidebar-overlay-open');
    })
});
