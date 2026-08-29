<?php
// Mirrors the reference logout.php: just clear the session.
// The "remember me" cookie is left alone on purpose (same as the reference
// code) — it only ever pre-fills the login form, it can't log anyone back in.
session_start();
session_unset();
session_destroy();

header("Location: index.php");
exit;
