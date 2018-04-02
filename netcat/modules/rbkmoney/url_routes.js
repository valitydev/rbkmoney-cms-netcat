urlDispatcher.addRoutes({
    'module.rbkmoney': NETCAT_PATH + 'modules/rbkmoney/admin.php?view=info'
})
.addPrefixRouter('module.rbkmoney.', function (path, params) {
    var view = path.split('.');
    var url = NETCAT_PATH + "modules/rbkmoney/admin.php?view=" + view[view.length - 1];
    if (params) {
        url += "&id=" + params;
    }
    mainView.loadIframe(url);
});