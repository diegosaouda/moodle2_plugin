var swfu;

window.onload = function() {
  var settings = {
    flash_url : M.cfg.wwwroot + "/local/lib/swfuplod/swfupload.swf",
    upload_url: M.cfg.wwwroot + "/local/boleto/upload.php",
    post_params: {},
    file_size_limit : "1 MB",
    file_types : "*.pdf",
    file_types_description : "Arquivos PDF",
    file_upload_limit : 0,
    file_queue_limit : 100,
    custom_settings : {
      progressTarget : "swfupload_queue",
      cancelButtonId : "swfupload_cancel"
    },
    debug: false,

    // Button settings
    button_image_url : M.cfg.wwwroot + "/local/lib/swfuplod/SmallSpyGlassWithTransperancy_17x18.png",
    button_placeholder_id : "swfupload_select",
    button_width: 300,
    button_height: 18,
    button_text : '<span class="button">Selecione os boletos <strong>(1 MB Max p/boleto)</strong></span>',
    button_text_style : '.button { font-family: Arial; font-size: 12pt; }',
    button_text_top_padding: 0,
    button_text_left_padding: 18,
    button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
    button_cursor: SWFUpload.CURSOR.HAND,

    // The event handler functions are defined in handlers.js
    file_queued_handler : fileQueued,
    file_queue_error_handler : fileQueueError,
    file_dialog_complete_handler : fileDialogComplete,
    upload_start_handler : uploadStart,
    upload_progress_handler : uploadProgress,
    upload_error_handler : uploadError,
    upload_success_handler : uploadSuccess,
    upload_complete_handler : uploadComplete,
    queue_complete_handler : queueComplete	// Queue plugin event
  };

  swfu = new SWFUpload(settings);
};

function swf_upload_upload_begin(){
  var mes = document.getElementById("upload_mes").value;
  
  if(mes <= 0){
    alert("Selecione o mês do boleto");
    return null;
  }
      
  swfu.setPostParams({
    "mes" : mes
  });
  
  if(!confirm("Tem certeza que deseja enviar agora?")){
    return null;
  }

  swfu.startUpload();  
}