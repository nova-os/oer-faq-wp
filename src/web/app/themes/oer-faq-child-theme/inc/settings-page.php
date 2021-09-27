<?php

class MySettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_menu', array( $this, 'menu_cleanup' )  );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_menu_page(
            'FAQ Import',
            'FAQ Import',
            'manage_options',
            'oer-faq-import',
            array( $this, 'create_admin_page' ),
            'dashicons-update-alt',
            22
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $importer = new OerFaqImporter();
        ?>
        <div class="wrap">
            <h1>FAQ Import</h1>
            <?php if (!$importer->isAuthorized()): ?>
                <p>
                    <a href="<?php echo esc_attr($importer->client->createAuthUrl()) ?>" class="button button-primary">Authorisieren</a>
                </p>
            <?php else: ?>
                <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                <pre>
                <?php
                    $stats = $importer->import();
                ?>
                </pre>
                <p>
                    <strong>Neu erstellt: </strong><?php echo $stats['inserted'] ?><br>
                    <strong>Aktualisiert </strong><?php echo $stats['updated'] ?><br>
                    <strong>Gelöscht: </strong><?php echo $stats['deleted'] ?><br>
                    <strong>Fehler: </strong><?php echo $stats['errors'] ?><br>
                </p>
                <?php else: ?>
                <form method="post" action="">
                    <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Import Starten"></p>
                </form>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php
    }


    public function menu_cleanup() {
        // remove_menu_page( 'index.php' );                  //Dashboard
        remove_menu_page( 'jetpack' );                    //Jetpack*
        remove_menu_page( 'edit.php' );                   //Posts
        // remove_menu_page( 'upload.php' );                 //Media
        // remove_menu_page( 'edit.php?post_type=page' );    //Pages
        remove_menu_page( 'edit-comments.php' );          //Comments
        // remove_menu_page( 'themes.php' );                 //Appearance
        // remove_menu_page( 'plugins.php' );                //Plugins
        // remove_menu_page( 'users.php' );                  //Users
        // remove_menu_page( 'tools.php' );                  //Tools
        // remove_menu_page( 'options-general.php' );        //Settings
    }
}

if( is_admin() )
    $my_settings_page = new MySettingsPage();
