			</div>
	        <!-- End container -->
	    </div>
	    <!-- End Page wrapper -->
	        
	    <!-- footer -->
	    <?php if(!$isLoginPage) : ?>
	    <footer class="footer text-center"> 
	        
	    </footer>
		<?php endif; ?>
	    <!-- End footer -->
	</div>
	<!-- End Wrapper -->

	<script>
        (function() {
            [].slice.call(document.querySelectorAll('.sttabs')).forEach(function(el) {
                new CBPFWTabs(el);
            });
        })();

        $(document).ready(function() {
        	$('.select2').select2();

        	jQuery('.mydatepicker').datepicker({
        		autoclose: true,
        		format: 'dd MM yyyy',
        	});

        	if($('.textarea_editor').length > 0) {
                $('.textarea_editor').each(function() {
                    $(this).wysihtml5({
                    	html: true
                    });
                });
            }

        	if ($(".mymce").length > 0) {
	            tinymce.init({
	                selector: "textarea.mymce",
	                min_height: 300,
					height: 300,
					theme: 'modern',
					fontsize_formats: '8pt 9pt 10pt 11pt 12pt 26pt 36pt',
					plugins: [
					    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
					    'searchreplace wordcount visualblocks visualchars code fullscreen',
					    'insertdatetime media nonbreaking save table contextmenu directionality',
					    'emoticons paste textcolor colorpicker textpattern imagetools',
					    'autoresize'
					],
					toolbar1: 'fontselect fontsizeselect styleselect | bold italic underline  | alignleft aligncenter alignright alignjustify | forecolor backcolor emoticons fullscreen',
					toolbar2: 'bullist numlist outdent indent | link image media | print preview',
						image_advtab: true
	            });
	        }
        });
    </script>
	<!-- Local Custom -->
    <script src="<?= Snl::app()->config()->theme_url ?>assets/js/local.js"></script>
</body>
</html>