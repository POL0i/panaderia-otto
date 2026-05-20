<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: 'Poppins', Arial, sans-serif; background: #FFF9F0; padding: 0; margin: 0;">
    <div style="max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        
        
        <div style="background: linear-gradient(135deg, #5D3A1A 0%, #8B4513 100%); padding: 25px; text-align: center;">
            <h1 style="color: white; margin: 0; font-size: 22px;">
                🍞 Panadería Otto
            </h1>
            <p style="color: #D2B48C; margin: 5px 0 0; font-size: 14px;">
                Reporte <?php echo e(ucfirst($tipo)); ?>

            </p>
        </div>

        
        <div style="padding: 25px;">
            <p style="color: #5D3A1A; font-size: 15px;">Hola,</p>
            
            <?php if(!empty($mensaje)): ?>
                <p style="color: #666; font-size: 14px; line-height: 1.6;">
                    <?php echo e($mensaje); ?>

                </p>
            <?php else: ?>
                <p style="color: #666; font-size: 14px; line-height: 1.6;">
                    Adjunto encontrarás el reporte <strong><?php echo e($tipo); ?></strong> 
                    <?php if(!empty($fecha_inicio) && !empty($fecha_fin)): ?>
                        del período <strong><?php echo e(\Carbon\Carbon::parse($fecha_inicio)->format('d/m/Y')); ?> - <?php echo e(\Carbon\Carbon::parse($fecha_fin)->format('d/m/Y')); ?></strong>
                    <?php endif; ?>
                    generado desde el sistema de Panadería Otto.
                </p>
            <?php endif; ?>

            <p style="color: #666; font-size: 14px;">
                El archivo PDF se adjunta a este correo.
            </p>

            <div style="background: #FFF5E6; border-radius: 8px; padding: 15px; margin: 20px 0;">
                <p style="margin: 0; color: #8B4513; font-size: 13px;">
                    <strong>📎 Archivo adjunto:</strong> reporte-<?php echo e($tipo); ?>.pdf
                </p>
            </div>

            <p style="color: #999; font-size: 12px; margin-top: 20px;">
                Este correo fue generado automáticamente por el sistema de gestión de Panadería Otto.
            </p>
        </div>

        
        <div style="background: #F5F0E8; padding: 15px 25px; text-align: center;">
            <p style="color: #8B4513; font-size: 12px; margin: 0;">
                © <?php echo e(date('Y')); ?> Panadería Otto. Todos los derechos reservados.
            </p>
        </div>
    </div>
</body>
</html><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/emails/reporte-pdf.blade.php ENDPATH**/ ?>