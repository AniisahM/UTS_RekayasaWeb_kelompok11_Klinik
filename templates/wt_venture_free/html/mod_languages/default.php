<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_languages
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('stylesheet', 'mod_languages/template.css', array(), true);
?>
<div class="mod-languages<?php echo $moduleclass_sfx ?>">
<?php if ($headerText) : ?>
	<div class="pretext"><p><?php echo $headerText; ?></p></div>
<?php endif; ?>

<?php if ($params->get('dropdown', 1)) : ?>
	<div name="lang" method="post" action="<?php echo htmlspecialchars(JUri::current()); ?>">
	<div class="uk-button-group language" onchange="document.location.replace(this.value);" >
		<div data-uk-dropdown="{mode:'hover'}">

		<?php foreach ($list as $language) : ?>
			<?php if ($language->active) : ?>
				<a class="uk-button" dir=<?php echo JLanguage::getInstance($language->lang_code)->isRTL() ? '"rtl"' : '"ltr"'?>>

					<?php if ($params->get('image', 1)):?>
						<?php echo JHtml::_('image', 'mod_languages/' . $language->image . '.gif', $language->title_native, array('title' => $language->title_native), true);?>
					<?php endif; ?>

					<?php echo $language->title_native;?>
				</a>
			<?php endif; ?>
		<?php endforeach; ?>

		<div class="uk-dropdown uk-dropdown-small">
		<ul class="uk-nav uk-nav-dropdown">

			<?php foreach ($list as $language) : ?>
				<li><a href="<?php echo $language->link;?>">

					<?php if ($params->get('image', 1)):?>
						<?php echo JHtml::_('image', 'mod_languages/' . $language->image . '.gif', $language->title_native, array('title' => $language->title_native), true);?>
					<?php endif; ?>

					<?php echo $language->title_native;?>

				</a></li>
			<?php endforeach; ?>

		</ul>
		</div>
		</div>
	</div>
	</div>
<?php else : ?>
	<ul class="<?php echo $params->get('inline', 1) ? 'lang-inline' : 'lang-block';?>">
	<?php foreach ($list as $language) : ?>
		<?php if ($params->get('show_active', 0) || !$language->active):?>
			<li class="<?php echo $language->active ? 'lang-active' : '';?>" dir="<?php echo JLanguage::getInstance($language->lang_code)->isRTL() ? 'rtl' : 'ltr' ?>">
			<a href="<?php echo $language->link;?>">
			<?php if ($params->get('image', 1)):?>
				<?php echo JHtml::_('image', 'mod_languages/' . $language->image . '.gif', $language->title_native, array('title' => $language->title_native), true);?>
			<?php else : ?>
				<?php echo $params->get('full_name', 1) ? $language->title_native : strtoupper($language->sef);?>
			<?php endif; ?>
			</a>
			</li>
		<?php endif;?>
	<?php endforeach;?>
	</ul>
<?php endif; ?>

<?php if ($footerText) : ?>
	<div class="posttext"><p><?php echo $footerText; ?></p></div>
<?php endif; ?>
</div>
