<?php if (! defined('EMPLOYEE_REPEAT_TABLE_STYLES')): define('EMPLOYEE_REPEAT_TABLE_STYLES', true); ?>
<style>
    .repeat-table-wrap { overflow-x:auto; border:1px solid var(--border); border-radius:10px; margin-bottom:12px; }
    .repeat-table { width:100%; border-collapse:collapse; font-size:13px; }
    .repeat-table th { background:var(--bg-body); color:var(--text-muted); font-size:11px; font-weight:700; letter-spacing:.02em; text-align:left; text-transform:uppercase; padding:10px; white-space:nowrap; }
    .repeat-table td { border-top:1px solid var(--border); color:var(--text-main); padding:8px 10px; vertical-align:top; overflow-wrap:break-word; min-width:80px; }
    .repeat-table td.repeat-no { color:var(--text-muted); text-align:center; width:40px; min-width:0; }
    .repeat-table td.repeat-empty { color:var(--text-muted); font-style:italic; padding:16px 10px; text-align:center; }
    .repeat-table .form-control { min-width:150px; padding:8px 10px; font-size:13px; }
    .repeat-table textarea.form-control { min-width:240px; }
    .repeat-table input[type="date"].form-control { min-width:140px; }
    .repeat-table input[type="number"].form-control { min-width:90px; }
    .repeat-table td.repeat-file { min-width:380px; }
    .repeat-table td.repeat-file .inline-document-card { margin-top:0; padding:8px 10px; }
    .repeat-table td.repeat-file .inline-document-description { display:none; }
    .repeat-table td.repeat-action { width:1%; white-space:nowrap; }
    .repeat-table .repeat-note { color:var(--text-muted); font-size:11px; }
    .repeat-table .repeat-stack { display:block; margin:0 0 6px; }
    .repeat-table .repeat-stack:last-child { margin-bottom:0; }
    .repeat-table .repeat-stack small, .repeat-table .repeat-stack-line small { color:var(--text-muted); display:block; font-size:10px; font-weight:600; }
    .repeat-table .repeat-stack-line { margin-bottom:5px; }
    .repeat-table .repeat-stack-line:last-child { margin-bottom:0; }
</style>
<?php endif; ?>
