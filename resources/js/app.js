import Quill from 'quill';
window.Quill = Quill;

function loadEditor(quillId, value, placeholder) {
    const toolbarOptions = [
        ['bold', 'italic', 'underline', 'strike'], // toggled buttons
        ['link', 'image'],

        [{
            'list': 'ordered'
        }, {
            'list': 'bullet'
        }],
        [{
            'color': []
        }, {
            'background': []
        }], // dropdown with defaults from theme
    ];
    let editor = new Quill("#" + quillId, {
        placeholder: placeholder,
        modules: {
            toolbar: toolbarOptions
        },
        theme: 'snow',
    });

    let delta = editor.clipboard.convert({
        html: value,
    });
    editor.setContents(delta, 'silent')

    editor.on('text-change', function() {
        let value = document.getElementById(quillId).children[0].innerHTML;
    })
}
window.loadEditor = loadEditor;
