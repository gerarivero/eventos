CKEDITOR.plugins.add('Destacado',
        {
            init: function (editor) {
                var pluginName = 'Destacado';
                editor.ui.addButton('Destacado',
                        {
                            label: 'Agregar texto destacado',
                            command: 'OpenWindow1',
                            icon: 'https://d30y9cdsu7xlg0.cloudfront.net/png/589672-200.png'
                        });
                var cmd = editor.addCommand('OpenWindow1', {exec: showMyDialog1});
            }
        });
function showMyDialog1(e) {
    //e.insertHtml('<div class="destacado">"...texto destacado..."</div><p></p>');
    var selection = e.getSelection();
    var el = selection.getStartElement();
    var parent = el.getParent();
    var text = parent.getText();
    if (el && parent.hasClass("destacado")) {
        el.remove(true);
        parent.remove(true);
    } else {
        var save = selection.getNative();
        var element = CKEDITOR.dom.element.createFromHtml('<div class="destacado">' + save + '</div><p></p>');
        e.insertElement(element);
    }
}
