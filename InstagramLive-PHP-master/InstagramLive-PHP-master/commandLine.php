<?php
define("autoArchive", in_array("-a", $argv), in_array("--auto-archive", $argv));

logM("Please wait while while the command line ensures that the live script is properly started!");
sleep(2);
logM("Command Line Ready! Type \"help\" for help.");
newCommand();


function newCommand()
{
    print "\n> ";
    $handle = fopen("php://stdin", "r");
    $line = trim(fgets($handle));
    if ($line == 'ecomments') {
        sendRequest("ecomments", null);
        logM("Enabled Comments!");
    } elseif ($line == 'dcomments') {
        sendRequest("dcomments", null);
        logM("Disabled Comments!");
    } elseif ($line == 'stop' || $line == 'end') {
        fclose($handle);
        $archived = "yes";
        if (!autoArchive) {
            logM("Would you like to keep the stream archived for 24 hours? Type \"yes\" to do so or anything else to not.");
            print "> ";
            $handle = fopen("php://stdin", "r");
            $archived = trim(fgets($handle));
        }
        if ($archived == 'yes') {
            sendRequest("end", ["yes"]);
        } else {
            sendRequest("end", ["no"]);
        }
        logM("Command Line Exiting! Stream *should* be ended.");
        sleep(2);
        exit();
    } elseif ($line == 'pin') {
        fclose($handle);
        logM("Please enter the comment id you would like to pin.");
        print "> ";
        $handle = fopen("php://stdin", "r");
        $commentId = trim(fgets($handle));
        //TODO add comment id length check
        logM("Assuming that was a valid comment id, the comment should be pinned!");
        sendRequest("pin", [$commentId]);
    } elseif ($line == 'unpin') {
        logM("Please check the other window to see if the unpin succeeded!");
        sendRequest("unpin", null);
    } elseif ($line == 'pinned') {
        logM("Please check the other window to see the pinned comment!");
        sendRequest("pinned", null);
    } elseif ($line == 'comment') {
        fclose($handle);
        logM("Please enter what you would like to comment.");
        print "> ";
        $handle = fopen("php://stdin", "r");
        $text = trim(fgets($handle));
        logM("Commented! Check the other window to ensure the comment was made!");
        sendRequest("comment", [$text]);
    } elseif ($line == 'url') {
        logM("Please check the other window for your stream url!");
        sendRequest("url", null);
    } elseif ($line == 'key') {
        logM("Please check the other window for your stream key!");
        sendRequest("key", null);
    } elseif ($line == 'info') {
        logM("Please check the other window for your stream info!");
        sendRequest("info", null);
    } elseif ($line == 'viewers') {
        logM("Please check the other window for your viewers list!");
        sendRequest("viewers", null);
    } elseif ($line == 'questions') {
        logM("Please check the other window for you questions list!");
        sendRequest("questions", null);
    } elseif ($line == 'showquestion') {
        fclose($handle);
        logM("Please enter the question id you would like to display.");
        print "> ";
        $handle = fopen("php://stdin", "r");
        $questionId = trim(fgets($handle));
        logM("Please check the other window to make sure the question was displayed!");
        sendRequest('showquestion', [$questionId]);
    } elseif ($line == 'hidequestion') {
        logM("Please check the other window to make sure the question was removed!");
        sendRequest('hidequestion', null);
    } elseif ($line == 'help') {
        logM("Commands:\n
        help - Prints this message\n
        url - Prints Stream URL\n
        key - Prints Stream Key\n
        info - Grabs Stream Info\n
        viewers - Grabs Stream Viewers\n
        ecomments - Enables Comments\n
        dcomments - Disables Comments\n
        pin - Pins a Comment\n
        unpin - Unpins a comment if one is pinned\n
        pinned - Gets the currently pinned comment\n
        comment - Comments on the stream\n
        questions - Shows all questions from the stream\n
        showquestion - Displays question on livestream\n
        hidequestion - Hides displayed question if one is displayed\n
        stop - Stops the Live Stream");
    } else {
        logM("Invalid Command. Type \"help\" for help!");
    }
    fclose($handle);
    newCommand();
}

function sendRequest(string $cmd, $values)
{
    /** @noinspection PhpComposerExtensionStubsInspection */
    file_put_contents(__DIR__ . '/request', json_encode([
        'cmd' => $cmd,
        'values' => isset($values) ? $values : [],
    ]));
    logM("Please wait while we ensure the live script has received our request.");
    sleep(2);
}

/**
 * Logs a message in console but it actually uses new lines.
 */
function logM($message)
{
    print $message . "\n";
}