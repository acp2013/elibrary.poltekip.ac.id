<?php
/**
 * @Created by          : Waris Agung Widodo (ido.alit@gmail.com)
 * @Date                : 2019-01-29 23:33
 * @File name           : vegas.js.php
 */

?>

<script>
  $('.c-header, .vegas-slide').vegas({
        delay: <?= $sysconf['template']['classic_slide_delay']; ?>,
        timer: false,
        transition: '<?= $sysconf['template']['classic_slide_transition']; ?>',
        animation: '<?= $sysconf['template']['classic_slide_animation']; ?>',
        slides: [
            { src: "<?php echo CURRENT_TEMPLATE_DIR; ?>assets/images/banner1.jpg" },
            { src: "<?php echo CURRENT_TEMPLATE_DIR; ?>assets/images/banner2.jpg" },
            { src: "<?php echo CURRENT_TEMPLATE_DIR; ?>assets/images/banner3.jpg" },
            { src: "<?php echo CURRENT_TEMPLATE_DIR; ?>assets/images/banner4.JPG" },
            { src: "<?php echo CURRENT_TEMPLATE_DIR; ?>assets/images/banner5.jpg" },
            { src: "<?php echo CURRENT_TEMPLATE_DIR; ?>assets/images/banner6.JPG" },
            { src: "<?php echo CURRENT_TEMPLATE_DIR; ?>assets/images/banner7.JPG" },
            { src: "<?php echo CURRENT_TEMPLATE_DIR; ?>assets/images/banner8.JPG" },
            { src: "<?php echo CURRENT_TEMPLATE_DIR; ?>assets/images/banner9.JPG" },
            { src: "<?php echo CURRENT_TEMPLATE_DIR; ?>assets/images/banner10.JPG" },
            { src: "<?php echo CURRENT_TEMPLATE_DIR; ?>assets/images/banner11.jpg" }
        ]
    });
</script>
