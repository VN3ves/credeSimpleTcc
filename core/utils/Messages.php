<?php
class Messages {

	public function __construct()
	{
		// Construtor carrega o arquivo do SweetAlert2
	}

    final public function info($message = null){
        if(empty($message)){ return; }
        
        return '<script>
            Swal.fire({
                toast: true,
                position: "top-end",
                icon: "info",
                title: "' . addslashes($message) . '",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                customClass: { popup: "colored-toast" }
            });
        </script>';
    }
    
    final public function success($message = null){
        if(empty($message)){ return; }
        
        return '<script>
            Swal.fire({
                toast: true,
                position: "top-end",
                icon: "success",
                title: "' . addslashes($message) . '",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                customClass: { popup: "colored-toast" }
            });
        </script>';
    }
    
    final public function error($message = null){
        if(empty($message)){ return; }
        
        return '<script>
            Swal.fire({
                toast: true,
                position: "top-end",
                icon: "error",
                title: "' . addslashes($message) . '",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                customClass: { popup: "colored-toast" }
            });
        </script>';
    }
    
    final public function questionYesNo($message = null, $captionYes = null, $captionNo = null, $linkYes = null, $linkNo = null){
        if(empty($message) && empty($captionYes) && empty($captionNo) && empty($linkYes) && empty($linkNo)){ return; }
        
        return '<script>
            Swal.fire({
                title: "' . addslashes($message) . '",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#2c3e50",
                cancelButtonColor: "#c0392b",
                confirmButtonText: "' . addslashes($captionYes ?: 'Sim') . '",
                cancelButtonText: "' . addslashes($captionNo ?: 'Não') . '"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "' . addslashes($linkYes) . '";
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    window.location.href = "' . addslashes($linkNo) . '";
                }
            });
        </script>';
    }
}
?>