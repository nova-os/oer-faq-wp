<?php


class OerFaqImporter
{

    private $tokenPath = null;
    public $client = null;
    private $synonyms;

    public function __construct()
    {
        $rootDir = dirname(dirname(ABSPATH)) . '/';
        $credentialPath = $rootDir  . 'credentials.json';
        $this->tokenPath = $rootDir . 'auth_token.json';
        $this->client = new Google_Client();
        $this->client->setApplicationName('OER FAQ Importer');
        $this->client->setScopes(Google_Service_Sheets::SPREADSHEETS_READONLY);
        $this->client->setAuthConfig($credentialPath);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('select_account consent');
        $this->loadAcccessToken();
    }

    public function isAuthorized()
    {
        // If there is no previous token or it's expired.
        if ($this->client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($this->client->getRefreshToken()) {
                $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
                $this->saveAccessToken();
            } else {
                return false;
            }
        }
        return true;
    }


    public function auth($authCode)
    {
        // Exchange authorization code for an access token.
        $accessToken = $this->client->fetchAccessTokenWithAuthCode($authCode);
        $this->client->setAccessToken($accessToken);

        // Check to see if there was an error.
        if (array_key_exists('error', $accessToken)) {
            throw new Exception(join(', ', $accessToken));
        }
        $this->saveAccessToken();
    }

    private function csvToArray($value)
    {
        $result = preg_split('/(\r\n|\n|\r|,)/', $value);
        $result = array_map('trim', $result);
        $result = array_filter($result, function ($c) {
            return !empty($c);
        });
        return $result;
    }

    private function nlsvToArray($value)
    {
        $result = explode("\n", $value);
        $result = array_map('trim', $result);
        $result = array_filter($result, function ($c) {
            return !empty($c);
        });
        return $result;
    }

    private function textVal($row, $index) {
        return isset($row[$index]) ? $row[$index] : '';
    }

    public static function normalize($text, $synonyms) {
        foreach($synonyms as $word => $regex) {
            $text = preg_replace($regex, "$1${word}$3", $text);
        }
        return $text;
    }

    public function import()
    {
        global $wpdb;
        $time = time();
        $table = "{$wpdb->prefix}faq";

        $stats = [
            'errors' => 0,
            'deleted' => 0,
            'updated' => 0,
            'inserted' => 0,
        ];

        $service = new Google_Service_Sheets($this->client);
        // Prints the names and majors of students in a sample spreadsheet
        // https://docs.google.com/spreadsheets/d/1SzR8ZBYUAFZ0HLxSDcKYhc-oEsYzeu5fcSOu5-MA55k/edit
        $spreadsheetId = '1SzR8ZBYUAFZ0HLxSDcKYhc-oEsYzeu5fcSOu5-MA55k';

        $range = 'Schlagworte!A2:B';
        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $rows = $response->getValues();

        $this->synonyms = [];

        foreach ($rows as $row) {
            if (isset($row[1])) {
                $synonyms = $this->csvToArray($row[1]);
                if (is_array($synonyms) && !empty($synonyms)) {
                    $regex = '/(\b)(' . implode('|', array_map(function($s) { return preg_quote($s, '/'); }, $synonyms)) . ')(\b)/i';
                }
                $this->synonyms[str_replace([' ', '-'], '_', $row[0])] = $regex;
            }
        }

        if (get_option('oer_faq_synonyms')) {
            update_option('oer_faq_synonyms', $this->synonyms);
        } else {
            add_option('oer_faq_synonyms', $this->synonyms, '', false);
        }

        $range = 'Q&As!A2:AQ';
        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $rows = $response->getValues();


        // Q&As
        $range = 'Q&As!A2:AQ' . (count($rows) + 1);
        unset($rows);
        $rows = $this->getRawValues($service, $spreadsheetId, $range);

        $post_modified = date( 'Y-m-d H:i:s', $time );
        $post_mofified_gmt = gmdate( 'Y-m-d H:i:s', $time );

        foreach ($rows as $row) {
            $question_id = trim($row[0]);
            if (empty($question_id)) {
                continue;
            }

            $related_questions = $this->nlsvToArray($this->textVal($row, 34));
            $tags = $this->csvToArray($this->textVal($row, 35));
            $categories = $this->nlsvToArray($this->textVal($row, 37));


            $data = [
                'question_id' => $question_id,
                'question' => $this->textVal($row, 2),
                'awnser' => $this->textVal($row, 4),
                'content' => $this->textVal($row, 5),
                'license_type' => $this->textVal($row, 13),
                'license_title' => $this->textVal($row, 14),
                'license_source_link' => $this->textVal($row, 15),
                'license_authors' => $this->textVal($row, 16),
                'license' => $this->textVal($row, 17),
                'license_link' => $this->textVal($row, 18),
                'license_edited_by' => $this->textVal($row, 19),
                'license_new' => $this->textVal($row, 20),
                'license_new_link' => $this->textVal($row, 21),
                'license_edit_notice' => $this->textVal($row, 22),
                'license_2_type' => $this->textVal($row, 23),
                'license_2_title' => $this->textVal($row, 24),
                'license_2_source_link' => $this->textVal($row, 25),
                'license_2_authors' => $this->textVal($row, 26),
                'license_2' => $this->textVal($row, 27),
                'license_2_link' => $this->textVal($row, 28),
                'license_2_edited_by' => $this->textVal($row, 29),
                'license_2_new' => $this->textVal($row, 30),
                'license_2_new_link' => $this->textVal($row, 31),
                'license_2_edit_notice' => $this->textVal($row, 32),
                'categories' => $this->textVal($row, 37),
                'tags' => implode(', ', $tags),
                'related_questions' => implode(', ', $related_questions),
            ];

            $data['content_norm'] = static::normalize($data['content'], $this->synonyms);
            $data['awnser_norm'] = static::normalize($data['awnser'], $this->synonyms);
            $data['tags_norm'] = static::normalize($data['tags'], $this->synonyms);
            $data['question_norm'] = static::normalize($data['question'], $this->synonyms);


            $post_data = array(
                'post_title'    => wp_strip_all_tags($data['question']),
                'post_content'  => $data['content'],
                'post_status'   => 'publish',
                'post_author'   => get_current_user_id(),
                'post_type'     => 'faq',
                'post_modified'     => $post_modified,
                'post_modified_gmt' => $post_mofified_gmt,
            );

            // if ($question_id === 'CC-1-2-4') {
            //     var_dump($data);
            //     die();
            // }



            $post_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM {$table} WHERE question_id = %s", $question_id));
            if (isset($post_id)) {
                $wpdb->update($table, $data, ['question_id' => $question_id]);
                $post_data['ID'] = $post_id;
                $post_id = wp_update_post($post_data);
                if (!is_wp_error($post_id)) {
                    //the post is valid
                } else {
                    //there was an error in the post insertion,
                    echo $post_id->get_error_message(). ")\n";;
                    $stats['errors']++;
                    continue;
                }
                $stats['updated']++;
            } else {
                $post_id = wp_insert_post($post_data);
                if (!is_wp_error($post_id)) {
                    //the post is valid
                } else {
                    //there was an error in the post insertion,
                    echo $post_id->get_error_message(). ")\n";;
                    $stats['errors']++;
                    continue;
                }
                $data['post_id'] = $post_id;
                $wpdb->insert($table, $data);
                $stats['inserted']++;
            }
            $result = wp_set_post_terms($post_id, $tags, 'faq_tag');
            if (is_wp_error($result)) {
                echo $result->get_error_message() . ' (' . implode(',', $tags) . ")\n";
                $stats['errors']++;
            }
            $result = wp_set_post_terms($post_id, $categories, 'faq_category');
            if (is_wp_error($result)) {
                echo $result->get_error_message(). ' (' . implode(',', $tags) . ")\n";
                $stats['errors']++;
            }
        }

        // delete untouched posts:
        $untouched = $wpdb->get_results( "SELECT ID FROM {$wpdb->posts} WHERE post_modified < '{$post_modified}' AND post_type = 'faq' AND post_status = 'publish'" );
        foreach($untouched as $p) {
            wp_trash_post($p->ID);
            $wpdb->delete($table, ['post_id' => $p->ID], ['%d']);
            $stats['deleted']++;
        }
        return $stats;
    }


    function getRawValues($service, $spreadsheetId, $range)
    {
        // This script uses the method of "spreadsheets.get".
        $sheets = $service->spreadsheets->get($spreadsheetId, ["ranges" => [$range], "fields" => "sheets"])->getSheets();

        // Following script is a sample script for retrieving "textFormat" and "textFormatRuns".
        $data = $sheets[0]->getData();
        $rowData = $data[0]->getRowData();
        $res = [];
        foreach ($rowData as $i => $row) {
            $temp = [];
            foreach ($row->getValues() as $j => $value) {
                $html = '';
                if (isset($value['textFormatRuns'])) {
                    $html = $this->getFormattedHtml($value);
                } else if($value['formattedValue']) {
                    $html = $value->getFormattedValue();
                }
                // if ($temp[0] === 'CC-2-18-1' && $j === 4) {
                //     var_dump($value);
                //     die();
                // }
                $temp[] = $html;
            }
            $res[] = $temp;
        }

        return $res;
    }


    function getFormattedHtml($cell) {
        $formattedHtml = "";
        // get plain text
        $plain_text = $cell->getFormattedValue();
        // get textFormatRuns
        $textFormatRuns = $cell->getTextFormatRuns();

        // loop over the textFormatRuns
        $len = count($textFormatRuns);
        for ($i=0; $i < $len; $i++) {
          $currentRunStart = $textFormatRuns[$i]['startIndex'];
          $substring = "";
          if ($i == $len - 1) {
            $substring = mb_substr($plain_text, $currentRunStart);
          } else {
            $currentRunEnd = $textFormatRuns[$i + 1]['startIndex'];
            $substring = mb_substr($plain_text, $currentRunStart, $currentRunEnd - $currentRunStart);
          }

          $run = $textFormatRuns[$i];
          // is inside html attribute?
          $is_attr = ($currentRunStart > 0 && mb_substr($plain_text, $currentRunStart - 1, 1) === '"');

          if (isset($run['format']['link']) && !$is_attr) {
            $uri = $run['format']['link']->getUri();
            $formattedHtml .= "<a href=\"${uri}\" target=\"_blank\">$substring</a>";
          } else {
            $formattedHtml .= $substring;
          }
        }

        return($formattedHtml);
      }

    private function loadAcccessToken()
    {
        // Load previously authorized token from a file, if it exists.
        // The file token.json stores the user's access and refresh tokens, and is
        // created automatically when the authorization flow completes for the first
        // time.
        if (file_exists($this->tokenPath)) {
            $accessToken = json_decode(file_get_contents($this->tokenPath), true);
            $this->client->setAccessToken($accessToken);
        }
    }

    private function saveAccessToken()
    {
        // Save the token to a file.
        file_put_contents($this->tokenPath, json_encode($this->client->getAccessToken()));
    }
}

// register oauth callback
if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/google-oauth-callback?') === 0 && isset($_GET['code'])) {
    $importer = new OerFaqImporter();
    $importer->auth($_GET['code']);
    wp_redirect(get_admin_url(null, 'admin.php') . '?page=oer-faq-import');
    die();
}
