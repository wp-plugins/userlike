<?php if(!$in_userlike) exit; ?>
			<div class="wrap">
				<?php screen_icon() ?>
				<h2><?php _e('Userlike', 'userlike') ?></h2>
				<div class="metabox-holder meta-box-sortables ui-sortable pointer">
					<div class="postbox" style="float:left;width:30em;margin-right:20px;width:500px;">
						<h3 class="hndle"><span><?php _e('Userlike Settings', 'userlike') ?></span></h3>
						<div class="inside" style="padding: 0 10px">
							<p style="text-align:center"><a href="http://userlike.com/" title="Userlike"><img src="<?php echo $plugin_dir; ?>userlike.png" alt="Userlike" /></a></p>
							<form method="post" action="options.php">
								<?php settings_fields('userlike'); ?>
								<p>
									<label for="userlike_secret"><?php echo __('Your Userlike Secret', 'userlike') ?></label><br />

									<input type="text" name="userlike_secret" value="<?php echo get_option('userlike_secret'); ?>" style="width:100%" />
								</p>
								<p class="submit">
									<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
									<input type="button" class="button-secondary" value="Help!" onClick="userlikeChat(); return false;" />
								</p>
							</form>

							<p style="color:#999239;background-color:#ffffe0;font-size:smaller;padding:0.4em 0.6em !important;border:1px solid #e6db55;-moz-border-radius:3px;-khtml-border-radius:3px;-webkit-border-radius:3px;border-radius:3px"><?php printf(__('Don&rsquo;t have an Userlike account? No problem! %1$sRegister for an Userlike account%2$sRegister for an Userlike account!%3$s', 'userlike'), '<a href="http://userlike.com/" title="', '">', '</a>') ?></p>
						</div>
					</div>									
				</div>
			</div>

<script src="//userlike.com/static/chat/javascripts/userlike.min.js"></script>
<script type="text/javascript">
					userlikeInit('a4e99b2f-2d7c-3785-ae0f-231eb2a7282a',
					'a5156bc60c33dae45919ac6b9bb22a8b8ac7556f6d5077d21514059591695837',
					'eyJwcm9hY3RpdmVfdGltZW91dCI6IDMwLCAib3JpZW50YXRpb24iOiAicmlnaHQiLCAiaGFzX3JlZ2lzdHJhdGlvbiI6IGZhbHNlLCAicHJvYWN0aXZlX21lc3NhZ2UiOiAiSGVsbG8sIGhvdyBjYW4gaSBoZWxwIHlvdT8iLCAibGFuZyI6ICJlbiIsICJzdXBwb3J0X3NzbCI6IHRydWUsICJ0cmFja2luZyI6IG51bGwsICJ0YWJfY29sb3IiOiAiZ3JlZW4iLCAic3VwcG9ydF9zY3JlZW5zaG90IjogdHJ1ZSwgInByb2FjdGl2ZV9tb2RlIjogImFjdGl2ZSIsICJpc19wcm9hY3RpdmUiOiBmYWxzZSwgInN1cHBvcnRfb2ZmbGluZW1vZGUiOiB0cnVlLCAic3VwcG9ydF9jaGF0X3N0YXRlIjogdHJ1ZX0=');
</script>