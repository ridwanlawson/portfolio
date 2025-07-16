
{ pkgs }: {
  deps = [
    pkgs.nodePackages.vscode-langservers-extracted
    pkgs.nodePackages.typescript-language-server
    pkgs.php82
    pkgs.php82Extensions.pdo
    pkgs.php82Extensions.sqlite3
  ];
}
