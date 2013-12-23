/* **********************
   Event Handlers
   These are my custom event handlers to make my
   web application behave the way I went when SWFUpload
   completes different tasks.  These aren't part of the SWFUpload
   package.  They are part of my application.  Without these none
   of the actions SWFUpload makes will show up in my application.
   ********************** */
function fileQueued(file) {
  
  document.getElementById("swfupload_status_remainder").innerHTML = this.getStats().files_queued;
  
	try {
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setStatus("aguardando envio...");
		progress.toggleCancel(true, this);

	} catch (ex) {
		this.debug(ex);
	}

}

function fileQueueError(file, errorCode, message) {
  
  document.getElementById("swfupload_status_remainder").innerHTML = this.getStats().files_queued;
  document.getElementById("swfupload_status_error").innerHTML = parseInt(document.getElementById("swfupload_status_error").innerHTML) + 1;
  
	try {
		if (errorCode === SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED) {
			alert("Muitos arquivos na fila você só pode selecionar " + this.settings.file_queue_limit);
			return;
		}

		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setError();
		progress.toggleCancel(false);

		switch (errorCode) {
		case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
			progress.setStatus("Arquivo é maior do que o permitido");
			this.debug("Error Code: File too big, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
			progress.setStatus("O arquivo não pode ser vazio");
			this.debug("Error Code: Zero byte file, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
			progress.setStatus("Tipo de arquivo inválido");
			this.debug("Error Code: Invalid File Type, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		default:
			if (file !== null) {
				progress.setStatus("Erro não conhecido");
			}
			this.debug("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		}
	} catch (ex) {
        this.debug(ex);
    }
}

function fileDialogComplete(numFilesSelected, numFilesQueued) {

  document.getElementById("swfupload_status_total").innerHTML = parseInt(document.getElementById("swfupload_status_total").innerHTML) + numFilesSelected;

	try {
    
		if (numFilesSelected > 0) {
			document.getElementById(this.customSettings.cancelButtonId).disabled = false;
      document.getElementById("swfupload_begin").disabled = false;
		}		
				
	} catch (ex)  {
        this.debug(ex);
	}
}

function uploadStart(file) {
    
	try {
		/* I don't want to do any file validation or anything,  I'll just update the UI and
		return true to indicate that the upload should start.
		It's important to update the UI here because in Linux no uploadProgress events are called. The best
		we can do is say we are uploading.
		 */
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setStatus("carregando...");
		progress.toggleCancel(true, this);
	}
	catch (ex) {}
	
	return true;
}

function uploadProgress(file, bytesLoaded, bytesTotal) {
  
	try {
		var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);

		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setProgress(percent);
		progress.setStatus("carregando... " + percent);
	} catch (ex) {
		this.debug(ex);
	}
}

function uploadSuccess(file, serverData) {
  
  document.getElementById("swfupload_status_remainder").innerHTML = this.getStats().files_queued;

  var data = eval("(" + serverData + ")");
    
	try {
		var progress = new FileProgress(file, this.customSettings.progressTarget);
                
        if(data.success){
            progress.setComplete();
            progress.setStatus("Completo.");

            document.getElementById("swfupload_status_success").innerHTML = parseInt(document.getElementById("swfupload_status_success").innerHTML) + 1;
        }
        else {
            progress.setError();
            progress.setStatus(data.message);

            document.getElementById("swfupload_status_error").innerHTML = parseInt(document.getElementById("swfupload_status_error").innerHTML) + 1;
        }
    
		progress.toggleCancel(false);

	} catch (ex) {
		this.debug(ex);
	}
}

function uploadError(file, errorCode, message) {

  document.getElementById("swfupload_status_remainder").innerHTML = this.getStats().files_queued;
  document.getElementById("swfupload_status_error").innerHTML = parseInt(document.getElementById("swfupload_status_error").innerHTML) + 1;

	try {
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setError();
		progress.toggleCancel(false);

		switch (errorCode) {
		case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:
			progress.setStatus("Erro no upload: " + message);
			this.debug("Error Code: HTTP Error, File name: " + file.name + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:
			progress.setStatus("Upload falhou");
			this.debug("Error Code: Upload Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.IO_ERROR:
			progress.setStatus("Erro no servidor (IO)");
			this.debug("Error Code: IO Error, File name: " + file.name + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR:
			progress.setStatus("Erro de segurança");
			this.debug("Error Code: Security Error, File name: " + file.name + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
			progress.setStatus("Limite do upload atingido.");
			this.debug("Error Code: Upload Limit Exceeded, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.FILE_VALIDATION_FAILED:
			progress.setStatus("Falha na validação. Upload foi cancelado.");
			this.debug("Error Code: File Validation Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
			// If there aren't any files left (they were all cancelled) disable the cancel button
			if (this.getStats().files_queued === 0) {
				document.getElementById(this.customSettings.cancelButtonId).disabled = true;
                document.getElementById("swfupload_begin").disabled = true;
			}
			progress.setStatus("Cancelado");
			progress.setError();
      //progress.setCancelled();
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
			progress.setStatus("Parado");
			break;
		default:
			progress.setStatus("Erro não conhecido: " + errorCode);
			this.debug("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		}
	} catch (ex) {
        this.debug(ex);
    }
}

function uploadComplete(file) {	
}

function queueComplete(numFilesUploaded) {
  document.getElementById(this.customSettings.cancelButtonId).disabled = true;
  document.getElementById("swfupload_begin").disabled = true;
}
