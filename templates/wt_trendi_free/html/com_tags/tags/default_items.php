<?php
/**
 * @package Helix Ultimate Framework
 * @author JoomShaper https://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2018 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/

defined ('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

if (JVERSION < 4)
{
	HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers');
	HTMLHelper::_('behavior.core');
	JLoader::register('TagsHelperRoute', JPATH_BASE . '/components/com_tags/helpers/route.php');
}
else
{
	$wa = $this->document->getWebAssetManager();
	$wa->useScript('com_tags.tag-default');
}

// Get the user object.
$user = Factory::getUser();

// Check if user is allowed to add/edit based on tags permissions.
$canEdit      = $user->authorise('core.edit', 'com_tags');
$canCreate    = $user->authorise('core.create', 'com_tags');
$canEditState = $user->authorise('core.edit.state', 'com_tags');

$columns = $this->params->get('tag_columns', 1);

// Avoid division by 0 and negative columns.
if ($columns < 1)
{
	$columns = 1;
}

$bsspans = floor(12 / $columns);

if ($bsspans < 1)
{
	$bsspans = 1;
}

$bscolumns = min($columns, floor(12 / $bsspans));
$n         = count($this->items);

Factory::getDocument()->addScriptDeclaration("
		var resetFilter = function() {
		document.getElementById('filter-search').value = '';
	}
");

?>

<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm">
	<?php if ($this->params->get('filter_field') || $this->params->get('show_pagination_limit')) : ?>
		<fieldset class="filters d-flex justify-content-between mb-3">
			<?php if ($this->params->get('filter_field')) : ?>
				<div class="input-group">
					<label class="filter-search-lbl sr-only" for="filter-search">
						<?php echo Text::_('COM_TAGS_TITLE_FILTER_LABEL') . '&#160;'; ?>
					</label>
					<input type="text" name="filter-search" id="filter-search" value="<?php echo $this->escape($this->state->get('list.filter')); ?>" class="form-control" onchange="document.adminForm.submit();" title="<?php echo Text::_('COM_TAGS_FILTER_SEARCH_DESC'); ?>" placeholder="<?php echo Text::_('COM_TAGS_TITLE_FILTER_LABEL'); ?>">
					<span class="input-group-btn">
						<button type="button" name="filter-search-button" title="<?php echo Text::_('JSEARCH_FILTER_SUBMIT'); ?>" onclick="document.adminForm.submit();" class="btn btn-secondary">
							<span class="fas fa-search" aria-hidden="true"></span>
						</button>
						<button type="reset" name="filter-clear-button" title="<?php echo Text::_('JSEARCH_FILTER_CLEAR'); ?>" class="btn btn-secondary" onclick="resetFilter(); document.adminForm.submit();">
							<span class="fas fa-times" aria-hidden="true"></span>
						</button>
					</span>
				</div>
			<?php endif; ?>
			<?php if ($this->params->get('show_pagination_limit')) : ?>
				<div class="btn-group float-right">
					<label for="limit" class="sr-only">
						<?php echo Text::_('JGLOBAL_DISPLAY_NUM'); ?>
					</label>
					<?php echo $this->pagination->getLimitBox(); ?>
				</div>
			<?php endif; ?>

			<input type="hidden" name="filter_order" value="">
			<input type="hidden" name="filter_order_Dir" value="">
			<input type="hidden" name="limitstart" value="">
			<input type="hidden" name="task" value="">
		</fieldset>
	<?php endif; ?>

	<?php if ($this->items == false || $n === 0) : ?>
		<p><?php echo Text::_('COM_TAGS_NO_TAGS'); ?></p>
	<?php else : ?>
		<?php foreach ($this->items as $i => $item) : ?>

			<?php if ($n === 1 || $i === 0 || $bscolumns === 1 || $i % $bscolumns === 0) : ?>
				<ul class="category list-group">
			<?php endif; ?>

			<li class="list-group-item list-group-item-action">
				<?php if ((!empty($item->access)) && in_array($item->access, $this->user->getAuthorisedViewLevels())) : ?>
					<h3 class="mb-0">
						<a href="<?php echo Route::_(JVERSION < 4 ? TagsHelperRoute::getTagRoute($item->id . ':' . $item->alias) : Joomla\Component\Tags\Site\Helper\RouteHelper::getTagRoute($item->id . ':' . $item->alias)); ?>">
							<?php echo $this->escape($item->title); ?>
						</a>
					</h3>
				<?php endif; ?>

				<?php if ($this->params->get('all_tags_show_tag_image') && !empty($item->images)) : ?>
					<?php $images  = json_decode($item->images); ?>
					<span class="tag-body">
						<?php if (!empty($images->image_intro)) : ?>
							<?php $imgfloat = empty($images->float_intro) ? $this->params->get('float_intro') : $images->float_intro; ?>
							<div class="float-<?php echo htmlspecialchars($imgfloat); ?> item-image">
							<?php $imageOptions = []; ?>
								<?php if ($images->image_intro_caption) : ?>
										<?php $imageOptions['title'] = $images->image_intro_caption; ?>
										<?php $imageOptions['class'] = 'caption'; ?>
								<?php endif; ?>
								<?php echo HTMLHelper::_('image', $images->image_intro, $images->image_intro_alt, $imageOptions); ?>
							</div>
						<?php endif; ?>
					</span>
				<?php endif; ?>

				<div class="caption">
					<?php if ($this->params->get('all_tags_show_tag_description', 1)) : ?>
						<span class="tag-body">
							<?php echo HTMLHelper::_('string.truncate', $item->description, $this->params->get('all_tags_tag_maximum_characters')); ?>
						</span>
					<?php endif; ?>
					<?php if ($this->params->get('all_tags_show_tag_hits')) : ?>
						<span class="list-hits badge badge-info">
							<?php echo Text::sprintf('JGLOBAL_HITS_COUNT', $item->hits); ?>
						</span>
					<?php endif; ?>
				</div>
			</li>

			<?php if (($i === 0 && $n === 1) || $i === $n - 1 || $bscolumns === 1 || (($i + 1) % $bscolumns === 0)) : ?>
				</ul>
			<?php endif; ?>

		<?php endforeach; ?>
	<?php endif; ?>

	<?php // Add pagination links ?>
	<?php if (!empty($this->items)) : ?>
		<?php if (($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->pagesTotal > 1)) : ?>
			<div class="w-100">
				<?php if ($this->params->def('show_pagination_results', 1)) : ?>
					<p class="counter float-right pt-3 pr-2">
						<?php echo $this->pagination->getPagesCounter(); ?>
					</p>
				<?php endif; ?>
				<?php echo $this->pagination->getPagesLinks(); ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>
</form>