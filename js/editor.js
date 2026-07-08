import { ClassicEditor, Essentials, Bold, Italic, Mention, Font, FontSize, Paragraph, ImageInsert, Alignment, ImageStyle, Heading, Link, List, MediaEmbed, ListEditing, FindAndReplace, PasteFromOffice, PageBreak, AutoImage, ImageBlock, ImageUpload, Fullscreen, Table, Style, LinkImage, IndentBlock, Indent, ImageCaption, ImageResize, BlockQuote, HorizontalLine, DecoupledEditor, Clipboard, TableSelection, TableToolbar, Typing, TodoList, ShiftEnter, Enter, WordCount, Title} from 'ckeditor5';
import { Mermaid } from '@ckeditor/ckeditor5-mermaid';
import { Image, ImageToolbar, ImageInline } from '@ckeditor/ckeditor5-image';
import 'ckeditor5/ckeditor5.css';
import '@ckeditor/ckeditor5-mermaid/index.css';
import esTranslations from 'ckeditor5/translations/es.js';
import Quill from 'quill';
import { HtmlToDelta } from 'quill-delta-from-html';
import { QuillDeltaToHtmlConverter } from 'quill-delta-to-html';
import { Base64UploadAdapter } from '@ckeditor/ckeditor5-upload';


let editor = ClassicEditor.create({
		attachTo: document.querySelector( '#element-ct' ),
		licenseKey: 'GPL',
		plugins: [ Essentials, Bold, Italic, Font, Paragraph, ImageInsert, Mention, Alignment, Image, ImageToolbar, ImageStyle, Heading, Mermaid, MediaEmbed, Link, List, ListEditing, FindAndReplace, Fullscreen, AutoImage, ImageUpload, Base64UploadAdapter, PageBreak, PasteFromOffice, Table, Style, LinkImage, Indent, IndentBlock, ImageCaption, ImageResize, ImageInline, ImageBlock, BlockQuote, HorizontalLine, Clipboard, TodoList, TableSelection, TableToolbar, Typing, ShiftEnter, Notification, Enter, WordCount],
				toolbar: {
	    items:[
        'undo', 'redo', '|', 'bold', 'italic', '|', 'alignment', 'Image', 'Mermaid', '|', 'PageBreak', '|',
        'fontFamily', 'fontColor', 'fontBackgroundColor', 'fontsize', '|', '-', 'FindAndReplace', 'Fullscreen', 
        'WordCount', 'BlockQuote', 'Typing', 'ShiftEnter', 'LinkImage', 'Mention', 'ImageInsert', 'Enter', 'HorizontalLine', 'ImageCaption', 'outdent', 'indent', 'TableSelection', 'TableToolbar',
        'Style', '|', 'AutoImage', 'PasteFromOffice', 'numberedList', 'todoList', 'bulletedList', 'ImageInline' , 'ImageToolbar', 'ImageStyle', '|', 'heading', '|', 
        'link', 'List', 'ListEditing', 'Table',
      ],
      shouldNotGroupWhenFull: false
    },
    image: {
			toolbar: [ 'imageInline', 'toggleImageCaption', 'imageTextAlternative', 'ckboxImageEdit', 'imageStyle:inline', 'imageStyle:wrapText', 'imageStyle:full', 'imageStyle:side', 'imageStyle:alignLeft', 'imageStyle:alignRight'],
      styles: {
				options: ['alignLeft', 'alignRight', 'wrapText', 'full', 'side']
			}
		},
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
    ],
    table: {
            // Toolbar shown when a table cell content is selected
            contentToolbar: [
                'tableColumn', 
                'tableRow', 
                'mergeTableCells', 
                'tableCellProperties', 
                'tableProperties'
            ],
            // Toolbar shown when the entire table is selected
            tableToolbar: [ 
                'tableColumn', 
                'tableRow', 
                'mergeTableCells' 
            ]
        }
  });

  //let quill = new Quill('#editor');

  $(document).ready(function (e, d) {
    $(".ck.ck-reset.ck-editor.ck-rounded-corners").on("keydown", function (e) {
      if (e.key === 'Enter' && e.shiftKey) {
        e.preventDefault();
        e.stopPropagation();
        editor.then(function (v) {
        v.execute('shiftEnter');
      });
    } else if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        e.stopPropagation();
        editor.then(function (v) {
          v.execute('enter');
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
      
      if (delt) v.setData(delt);

      v.model.document.on("change:data", function (e) {

        //const delta = quill.clipboard.convert({ html: v.getData()});
        //$("#Observaciones").prop("value", JSON.stringify(delta));
        $("#Observaciones").prop("value", v.getData());
      });

      $("#mdal_ct").on("show.bs.modal", function (event) {
          let tr = event.relatedTarget;
          let id = tr.getAttribute('data-id-mv');
          let div = tr.childNodes[1];
          if (div) v.setData(div.innerHTML);
          $("#mdal_ct").prop("id_mv", id);
        });
        
        $("#bt-guardar").on("click", function (e) {
            let id = $("#mdal_ct").prop("id_mv");            
            let data = {data : v.getData(), ID : id} ;
            let request = $.ajax({
                type: "POST",
                cache: false,
                url: "/Controladores/modificarmovimientoobservacion.php",
                async: true,
                data: JSON.stringify(data),
                contentType: "application/json",
                processData: false,
                success: function (e) {
                  $("td[data-id-mv=" + id + "]").children()[0].innerHTML = v.getData();
                }
            });          
        })

    });
  });