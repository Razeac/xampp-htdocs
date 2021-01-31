<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">POD AMS <code>Client</code></a>
    <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link client_connect" href="#">Connect Client <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php">Students</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="faculty.php">Faculty</a>
        </li>
    </ul>
    <span class="navbar-text">
    PC NAME: <span class="client_name"><?=gethostbyaddr($_SERVER['REMOTE_ADDR']);?></span>
    IP: <span class="client_ip"><?=getenv("REMOTE_ADDR"); ?> </span>
    © John Louis <code class="system_date"></code></span>
</nav>