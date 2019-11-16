<!DOCTYPE html>
<html><head><meta http-equiv="content-type" content="text/html; charset=utf-8" /></head><body>HEY

<?php
	header('Content-type: text/html; charset=utf-8');
	echo "hello";
	function saveImage(base64 = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAABmJLR0QA/wD/AP+gvaeTAAACUElEQVR4nO2ZsWsUQRTGv7daWdxFDJxVTrS1EqxSWJh/QRsFjxzRbOAqC8tsLSQg6i0nigliCm1FSJG/QLCyVDBWyWGRPaOd+1kkd1k0m9tbM/MC935wsDfM2/nmmze7924AwzAMwzAMYzwRrYFvvOGpyd3kMQLB9zOV1tub8ltDR6AxKEg59ytZhyAEGU7+TDaiiCpa/A9KyvxK8kSI65nWa9v15JmGCX63QH/ywEJOjxe1zerdKJLUlyR/jpOysJLER0weAJrdetIG6W1h/Ax0yMoL8JHAlf0OHwC5monwlgnuM+CwtBc8F/Ld4GsQvAf4NBPV9PVMcDtAzuRrX6v3KOCgLU0RNyZaGiY4vfn8am/pr7TvxHdyUluEcWOiJUAn09rsTiUPXWp0aoCAcwfX6LQb1RAizA8QthvVMGsCBXO5/Y8Bt1sg5TKAH0IsDZ18n30TAFmGYFfARy4lnnZ583j27CKARQDA7AiBIoyB+9j7OEXnp/AJwgzQFqCNGaAtQBszQFuANmaAtgBtzABtAdqYAdoCtDEDtAVoo2ZAiuBL/5oMPmvpcPqHyFGc36y83p7qEQBq3yprWjrGnsIHI1HEYKveuwXsrZ7P46silNVXeAt0L+zcFsoqAGzVewDwqoxQV5TVV/whSFw8CEovjajPPSX12WtQW4A2ZoC2AG3MAG0B2oy9AaVqAYpMhy93Hhy3mP+BgmkMP3v+h3LFEDEDYKZUrCtKTB4YYQtky9eTzijldeEMGJSvwsvlZHmC8snKa8MwDMMwDGM4fwBTgMyPg+SKEAAAAABJRU5ErkJggg==") {
		if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
			$data = substr($data, strpos($data, ',') + 1);
			$type = strtolower($type[1]); // jpg, png, gif

			if (!in_array($type, [ 'jpg', 'jpeg', 'png' ])) {
				echo 'invalid image type.';
			}

			$data = base64_decode($data);

			if ($data === false) {
				echo 'base64_decode failed.';
			}
		}
		else {
			echo 'did not match data URI with image data.';
		}
		file_put_contents("img.{$type}", $data);
		echo "Image Added.";
	}
	saveImage();
?>
</body></html>