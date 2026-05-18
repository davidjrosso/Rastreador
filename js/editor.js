import { ClassicEditor, Essentials, Bold, Italic, Font, Paragraph, Alignment, Image, Heading, Link, List, MediaEmbed, ListEditing, FindAndReplace, PasteFromOffice, PageBreak, AutoImage, Fullscreen, Table, Style, LinkImage, Indent, ImageCaption, BlockQuote, HorizontalLine, Typing, ShiftEnter, Enter, WordCount, Title} from 'ckeditor5';
import { Mermaid } from '@ckeditor/ckeditor5-mermaid';
import 'ckeditor5/ckeditor5.css';
import '@ckeditor/ckeditor5-mermaid/index.css';
import esTranslations from 'ckeditor5/translations/es.js';
import Quill from 'quill';
import { HtmlToDelta } from 'quill-delta-from-html';
import { QuillDeltaToHtmlConverter } from 'quill-delta-to-html';

let editor = ClassicEditor.create( {
		attachTo: document.querySelector( '#element-ct' ),
		licenseKey: 'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3ODAyNzE5OTksImp0aSI6IjMyNjUxN2FmLWU2NjUtNDk2OC05MzNlLTJkNTVhYmEyNWY2NiIsInVzYWdlRW5kcG9pbnQiOiJodHRwczovL3Byb3h5LWV2ZW50LmNrZWRpdG9yLmNvbSIsImRpc3RyaWJ1dGlvbkNoYW5uZWwiOlsiY2xvdWQiLCJkcnVwYWwiLCJzaCJdLCJ3aGl0ZUxhYmVsIjp0cnVlLCJsaWNlbnNlVHlwZSI6InRyaWFsIiwiZmVhdHVyZXMiOlsiKiJdLCJ2YyI6ImZhNmExNDU0In0.UShtGMJrmzcVN_wcIO2R3oqVoviKwud-ho74o41GFZttNtUgIYxIfCT8SZpY198yO53RyCEnmqx4RCy4L3hxhg', // Or 'GPL'.
		plugins: [ Essentials, Bold, Italic, Font, Paragraph, Alignment, Image, Heading, Mermaid, MediaEmbed, Link, List, ListEditing, FindAndReplace, Fullscreen, AutoImage, PageBreak, PasteFromOffice, Table, Style, LinkImage, Indent, ImageCaption, BlockQuote, HorizontalLine, Typing, ShiftEnter, Enter, WordCount],
		toolbar: [
    'undo', 'redo', '|', 'bold', 'italic', '|', 'alignment', 'Image', 'Mermaid', '|', 'PageBreak', '|',
    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|', '-', 'FindAndReplace', 'Fullscreen', 
    'WordCount', 'BlockQuote', 'Typing', 'ShiftEnter', 'LinkImage',  'Enter', 'HorizontalLine', 'ImageCaption', 
    'Indent', 'Style', '|', 'AutoImage', 'PasteFromOffice', 'numberedList', 'bulletedList', '|', 'heading', '|', 
    'link', 'List', 'ListEditing', 'Table', 'uploadImage',
		],
		root: {
			placeholder: ''
		},
    style: {
          definitions: [
            {
              name: 'Article category',
              element: 'h3',
              classes: [ 'category' ]
            },
            {
              name: 'Info box',
              element: 'p',
              classes: [ 'info-box' ]
            },
          ]
        },
    language: {
        ui: 'es',
        content: 'es' 
    },
    translations: [
        esTranslations
    ]
  });

  //let quill = new Quill('#editor');

  $(document).ready(function (e, d) {
    $(".ck.ck-reset.ck-editor.ck-rounded-corners").on("keyup", function (e) {
      if (e.key === 'Enter' && e.shiftKey) {
        editor.then(function (v) {
        v.execute('shiftEnter');
        d.preventDefault();
        v.stop();
      });
    } else if (e.key === 'Enter' && !e.shiftKey) {
        editor.then(function (v) {
          v.execute('enter');
          d.preventDefault();
          v.stop();
      });      
    }
    });
    editor.then(function (v) {
      v.editing.view.change(writer => {
          writer.setStyle('min-height', '190px', v.editing.view.document.getRoot());
      });

      let delt = $("#Observaciones").val();
      /*
      const converter = new QuillDeltaToHtmlConverter(JSON.parse(delt), {});
      v.setData(converter.convert());
      */
      v.setData(delt);

      v.model.document.on("change:data", function (e) {

        //const delta = quill.clipboard.convert({ html: v.getData()});
        //$("#Observaciones").prop("value", JSON.stringify(delta));
        $("#Observaciones").prop("value", v.getData());
      });

    });
  });
  