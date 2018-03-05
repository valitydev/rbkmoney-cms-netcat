urlDispatcher.addRoutes({
    'module.test': NETCAT_PATH + 'modules/rbk/admin.php?view=info'
})
.addPrefixRouter('module.rbk.', function (path, params) {
    var view = path.split('.');
    var url = NETCAT_PATH + "modules/rbk/admin.php?view=" + view[view.length - 1];
    if (params) {
        url += "&id=" + params;
    }
    mainView.loadIframe(url);
});