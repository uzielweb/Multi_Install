<?php
/**
 * @package     MultiInstall
 * @subpackage  com_multiinstall
 *
 * @copyright   (C) 2010 Chris Taylor, (C) 2026 Uziel Almeida Oliveira
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

/** @var \Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->getDocument()->getWebAssetManager();
$wa->useStyle('com_multiinstall.admin');
?>

<div class="com-multiinstall container-fluid p-0">
    <div class="row">
        <div class="col-12 col-xl-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <ul class="nav nav-tabs card-header-tabs" id="multiInstallTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active d-flex align-items-center gap-2"
                                    id="upload-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#tab-upload"
                                    type="button"
                                    role="tab"
                                    aria-controls="tab-upload"
                                    aria-selected="true">
                                <span class="fa fa-upload text-primary" aria-hidden="true"></span>
                                <span><?php echo Text::_('COM_MULTIINSTALL_TAB_UPLOAD'); ?></span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center gap-2"
                                    id="url-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#tab-url"
                                    type="button"
                                    role="tab"
                                    aria-controls="tab-url"
                                    aria-selected="false">
                                <span class="fa fa-globe text-primary" aria-hidden="true"></span>
                                <span><?php echo Text::_('COM_MULTIINSTALL_TAB_URL'); ?></span>
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-4">
                    <div class="tab-content" id="multiInstallTabsContent">
                        <!-- Aba Upload -->
                        <div class="tab-pane fade show active" id="tab-upload" role="tabpanel" aria-labelledby="upload-tab">
                            <div class="alert alert-info d-flex align-items-start gap-3 mb-4" role="alert">
                                <span class="fa fa-info-circle fa-lg mt-1 text-info" aria-hidden="true"></span>
                                <div><?php echo Text::_('COM_MULTIINSTALL_INSTRUCTIONS'); ?></div>
                            </div>

                            <form action="<?php echo Route::_('index.php?option=com_multiinstall'); ?>"
                                  method="post"
                                  enctype="multipart/form-data"
                                  name="uploadForm"
                                  id="uploadForm"
                                  class="form-validate">

                                <div class="mb-4">
                                    <label for="extfile" class="form-label fw-bold">
                                        <?php echo Text::_('COM_MULTIINSTALL_FIELD_FILE_LABEL'); ?> <span class="star text-danger">*</span>
                                    </label>
                                    <input type="file"
                                           class="form-control"
                                           name="extfile"
                                           id="extfile"
                                           accept=".zip"
                                           required />
                                    <div class="form-text"><?php echo Text::_('COM_MULTIINSTALL_FIELD_FILE_DESC'); ?></div>
                                </div>

                                <div class="d-flex align-items-center gap-2">
                                    <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                                        <span class="fa fa-cloud-upload-alt" aria-hidden="true"></span>
                                        <span><?php echo Text::_('COM_MULTIINSTALL_SUBMIT'); ?></span>
                                    </button>
                                </div>

                                <input type="hidden" name="task" value="upload" />
                                <?php echo HTMLHelper::_('form.token'); ?>
                            </form>
                        </div>

                        <!-- Aba URL -->
                        <div class="tab-pane fade" id="tab-url" role="tabpanel" aria-labelledby="url-tab">
                            <div class="alert alert-info d-flex align-items-start gap-3 mb-4" role="alert">
                                <span class="fa fa-info-circle fa-lg mt-1 text-info" aria-hidden="true"></span>
                                <div><?php echo Text::_('COM_MULTIINSTALL_URL_INSTRUCTIONS'); ?></div>
                            </div>

                            <form action="<?php echo Route::_('index.php?option=com_multiinstall'); ?>"
                                  method="post"
                                  name="urlForm"
                                  id="urlForm"
                                  class="form-validate">

                                <div class="mb-4">
                                    <label for="install_url" class="form-label fw-bold">
                                        <?php echo Text::_('COM_MULTIINSTALL_FIELD_URL_LABEL'); ?> <span class="star text-danger">*</span>
                                    </label>
                                    <input type="url"
                                           class="form-control"
                                           name="install_url"
                                           id="install_url"
                                           placeholder="<?php echo $this->escape(Text::_('COM_MULTIINSTALL_FIELD_URL_PLACEHOLDER')); ?>"
                                           required />
                                    <div class="form-text"><?php echo Text::_('COM_MULTIINSTALL_FIELD_URL_DESC'); ?></div>
                                </div>

                                <div class="d-flex align-items-center gap-2">
                                    <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                                        <span class="fa fa-download" aria-hidden="true"></span>
                                        <span><?php echo Text::_('COM_MULTIINSTALL_SUBMIT'); ?></span>
                                    </button>
                                </div>

                                <input type="hidden" name="task" value="url" />
                                <?php echo HTMLHelper::_('form.token'); ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h2 class="card-title h6 mb-0 d-flex align-items-center gap-2 text-dark">
                        <span class="fa fa-cube text-secondary" aria-hidden="true"></span>
                        <span><?php echo Text::_('COM_MULTIINSTALL'); ?></span>
                    </h2>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-0">
                        <?php echo Text::_('COM_MULTIINSTALL_CREDITS'); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
