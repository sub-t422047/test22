# GitHub 利用手順(メモ)
このガイドでは、ホストPCおよび仮想サーバーでのGitとGitHubの設定手順を説明します。
不足情報があれば、適宜追記をお願いします。


## 1. ホストPCにGitをインストール
1. Gitをインストールします。

2. ターミナルを開き、次のコマンドを実行して設定を行います。
   ```bash
   git config --global user.name "Your Name"
   git config --global user.email "your_email@example.com"
   git config --global init.defaultBranch main
   git config --global core.editor "code --wait"
   ```


## 2. 仮想サーバーにGitをインストール
1. 仮想サーバーにSSH接続し、次のコマンドを実行してGitをインストールします。
    ```bash
    sudo yum install git -y  # Rocky Linux 9の場合
    ```
    
2. 次のコマンドを実行して設定を行います。
    ```bash
    git config --global user.name "Your Name"
    git config --global user.email "your_email@example.com"
    git config --global init.defaultBranch main
    ```


## 3. VSCodeにRemote - SSHをインストール
1. VSCodeを開き、拡張機能（Extensions）を検索し、Remote - SSHをインストールします。

    
## 4. SSH鍵の生成
#### 4.1 ホストPCでSSH鍵を発行
1. ホストPCのターミナルで次のコマンドを実行してSSH鍵を生成します。パスフレーズは空のままにします。
    ```bash
    ssh-keygen -t rsa -b 4096 -C "your_email@example.com"
    ```
 
    * プロンプトが表示されたら、鍵のファイル名と場所をそのまま（デフォルト）にする場合、Enterキーを押します。
    * パスフレーズは空のままEnterキーを押します。

2. 鍵が生成されると、以下のようにメッセージが表示されます。これにより、鍵が生成されたことを確認できます。

    ```arduino
    Your identification has been saved in /home/your_username/.ssh/id_rsa
    Your public key has been saved in /home/your_username/.ssh/id_rsa.pub
    ```
    
#### 4.2 公開鍵を仮想サーバーに登録
1. 公開鍵の内容を確認します。次のコマンドを実行します。
     ```bash
     cat ~/.ssh/id_rsa.pub
     ```
     
2. 出力された内容（公開鍵）をコピーします。次に、仮想サーバーにSSH接続します。
     ```bash
     ssh userName@ServerIPAddress
     ```
     
3. 仮想サーバー上で、次のコマンドを実行して.sshディレクトリを作成し、適切な権限を設定します。
    ```bash
    mkdir -p ~/.ssh
    chmod 700 ~/.ssh
    ```

4. authorized_keysファイルに公開鍵を追加します。次のコマンドを実行します。
    ```bash
    echo "your_copied_public_key" >> ~/.ssh/authorized_keys
    ```
    * your_copied_public_keyは、先ほどコピーした公開鍵の内容に置き換えてください。

5. authorized_keysファイルの権限を設定します。
    ```bash
    chmod 600 ~/.ssh/authorized_keys
    ```

    
## 5. Remote - SSHの設定
1. VSCodeのRemote - SSH設定マークからconfigファイルを設定します。以下の内容を追加します。

    ```plaintext
    Host Test
        HostName ServerIPAddress
        User userName
        Port portNumber
        IdentityFile ~/.ssh/id_rsa  # ここはホストPCのSSH鍵のパス
    ```

2. ＋ボタンからSSH接続を選択し、Linuxを選びます。


## 6. GitHubアカウントを作成
1. GitHubにアクセスし、アカウントを作成します。


## 7. 仮想サーバーでSSH鍵を発行
1. 仮想サーバーでSSH鍵を発行する手順は、ホストPCでの手順と同様です。次のコマンドを実行します。

    ```bash
    ssh-keygen -t rsa -b 4096 -C "your_email@example.com"
    ```
    
2. 公開鍵を確認し、次のコマンドで内容を表示します。
    ```bash
    cat ~/.ssh/id_rsa.pub
    ```

3. この公開鍵をGitHubに登録します。GitHubの「Settings」→「SSH and GPG keys」→「New SSH key」で公開鍵を追加します。


## 8. 仮想サーバーからGitHubのリモートリポジトリにアクセス
1. GitHubで作成したリポジトリを仮想マシンのリモートリポジトリとして設定します。
    ```bash
    git remote add origin git@github.com:your_username/repository_name.git
    ```

2. 現在のリモートURLを確認します。
    ```bash
    git remote -v
    ```
    これにより、現在のリモート名とURLが表示されます。例えば、以下のような結果が表示されるかもしれません：
    ```bash
    origin    https://github.com/username/old-repo.git (fetch)
    origin    https://github.com/username/old-repo.git (push)
    ```
    
3. 仮想マシンからGitHubにSSH接続ができるかをテストします。
    ```bash
    ssh -T git@github.com 
    ```
    正常に接続されると、以下のようなメッセージが表示されます。
    ```bash
    Hi username! You've successfully authenticated, but GitHub does not provide shell access.
    ```
    このメッセージが表示されれば、SSH接続が正常に設定されています。


***
***

## 開発作業の流れ
### 1. ブランチを切る
  - 新しい機能や修正のためにブランチを作成します。
    ```bash
    git checkout -b new-branch-name
    ```
    
### 2. 状態確認
  - ローカルリポジトリの状態を確認します。
    ```bash
    git status
    ```
 
### 3. ステージング
  - 変更したファイルをステージングエリアに追加します。
    ```bash
    git add .
    ```
    
### 4. コミット
  - ステージングエリアにある変更をコミットします。
    ```bash
    git commit -m "Your commit message"
    ```
    
### 5. プッシュ
  - ローカルのブランチをリモートリポジトリにプッシュします。
    ```bash
    git push origin new-branch-name
    ```
    
### 6. プルリクエストを作成
  - GitHub上でプルリクエストを作成し、レビューを依頼します。

### 7. レビュー
  - 他のチームメンバーからのレビューを受けます。

### 8. マージ
  - プルリクエストが承認されたら、マージします。

### 9. 再度プッシュ
  - マージ後、リモートリポジトリを最新の状態に保つためにプッシュします。
    ```bash
    git push origin main  # メインブランチの場合
    ```


***
***


## 補足
### Github拡張機能
1. VSCodeにGitHub拡張機能をインストール
  - VSCodeを開き、サイドバーの「拡張機能」アイコンをクリックします。
  - 「GitHub Pull Requests and Issues」という拡張機能を検索してインストールします。

### スタッシュ
1. 作業ブランチでの変更を一時的に保存する
  - 通常、未コミットの変更内容はブランチ変更時に引き継がれる。
  - コミットせずに変更内容を保存でき、他のブランチに引き継がれない。
    ```bash
    git stash
    ```

### ローカルリポジトリからリモートリポジトリを作成する
1. GitHub上でリモートリポジトリを作成する
2. .gitignoreファイルを作成して、Gitで管理したくないファイルをリストアップします。

    ```plaintext
    # 例:
    *.log
    /node_modules
    /tmp
    ```
    .gitignoreにリストされたファイルはGitで追跡されません。
3. 管理するファイルをステージング・コミットする
    ```bash
    git add .
    git commit -m "Your commit message"
    ``` 
4. ローカルリポジトリをリモートにプッシュする
コミットした変更をGitHubのリモートリポジトリにプッシュします。
    ```bash
    git branch -M main  # ブランチ名を "main" に変更
    git push -u origin main
    ```


※
   ```
   sudo chmod -R g+w /home/seto/html/wp-content/themes/cocoon-child-master/
   ```
