----------------------
akismet
----------------------
Version: 0.1.0-dev1
Author: YJ Tso (@sepiariver)
Contact: yj@modx.com
----------------------

akismet is an integration of the Akismet API into MODX CMS.
Currently it only supports the following methods via the included akismetCommentCheck snippet:
verify_key
comment_check

akismetCommentCheck *should* work whether called as a FormIt hook, or as a standalone snippet with form submission fields passed to it via JSON.

Contributions are welcome/encouraged to integrate the other methods:
submit_spam
submit_ham
