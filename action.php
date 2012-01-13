<?php
/**
 * DokuWiki Plugin flashuploader (Action Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Michael Hamann <michael@content-space.de>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

if (!defined('DOKU_LF')) define('DOKU_LF', "\n");
if (!defined('DOKU_TAB')) define('DOKU_TAB', "\t");
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');

require_once DOKU_PLUGIN.'action.php';

class action_plugin_flashuploader extends DokuWiki_Action_Plugin {

    public function register(Doku_Event_Handler &$controller) {

       $controller->register_hook('HTML_UPLOADFORM_OUTPUT', 'AFTER', $this, 'handle_html_uploadform_output');
   
    }

    public function handle_html_uploadform_output(Doku_Event &$event, $param) {
        global $lang;
        $ns = $event->data->_hidden['ns'];
        $can_overwrite = $event->data->findElementById('dw__ow') !== false;
        // this code is a 1-to-1 copy from the DokuWiki release Rincewind
        // prepare flashvars for multiupload
        $opt = array(
            'L_gridname'  => $this->getLang('mu_gridname'),
            'L_gridsize'  => $this->getLang('mu_gridsize'),
            'L_gridstat'  => $this->getLang('mu_gridstat'),
            'L_namespace' => $this->getLang('mu_namespace'),
            'L_overwrite' => $lang['txt_overwrt'],
            'L_browse'    => $this->getLang('mu_browse'),
            'L_upload'    => $lang['btn_upload'],
            'L_toobig'    => $this->getLang('mu_toobig'),
            'L_ready'     => $this->getLang('mu_ready'),
            'L_done'      => $this->getLang('mu_done'),
            'L_fail'      => $this->getLang('mu_fail'),
            'L_authfail'  => $this->getLang('mu_authfail'),
            'L_progress'  => $this->getLang('mu_progress'),
            'L_filetypes' => $this->getLang('mu_filetypes'),
            'L_info'      => $this->getLang('mu_info'),
            'L_lasterr'   => $this->getLang('mu_lasterr'),

            'O_ns'        => ":$ns",
            'O_backend'   => DOKU_BASE.'lib/exe/mediamanager.php?'.session_name().'='.session_id(),
            'O_maxsize'   => php_to_byte(ini_get('upload_max_filesize')),
            'O_extensions'=> join('|',array_keys(getMimeTypes())),
            'O_overwrite' => $can_overwrite,
            'O_sectok'    => getSecurityToken(),
            'O_authtok'   => auth_createToken(),
        );
        $var = buildURLparams($opt);
        // output the flash uploader
?>
        </div>
        <div id="dw__flashupload">
        <div class="upload"><?php echo $lang['mu_intro']?></div>
        <?php echo html_flashobject(DOKU_BASE.'lib/plugins/flashuploader/multipleUpload.swf','500','190',null,$opt); ?>
<?php
    }

}

// vim:ts=4:sw=4:et:
