# 構成図

## アプリのゴール

**ファーストステップ**
- メンバー登録アプリを作成する
  - セキュリティを考慮する
  - テストコード対応
  - パブリック公開

**セカンドステップ**
- メンバー登録アプリを掲示板アプリにする

**サードステップ**
- 管理画面を作る

---

## DB

### ファーストステップ
メンバー登録アプリ

```
mst_member
- id(PK)
- username
- mail
- pass
- created_at
- updated_at
- deleted_at
```

### セカンドステップ
掲示板アプリ

```
mst_board
- id(PK)
- mem_name
- mem_code
- post
- created_at
- updated_at
- deleted_at
```

```
mst_posts
- id(PK)
- mem_code
- posts
- created_at
- updated_at
- deleted_at
```

### サードステップ
管理画面

```
mst_admin
- id(PK)
- admin_name
- admin_mem_code
- created_at
- updated_at
- deleted_at
```

```
history_edit
- id(PK)
- edit_name
- edit_board_id
- edit_board_detail
- edit_mem_name
- edit_mem_pass
- delete_board_id
- delete_mem_name
- delete_mem_code
- created_at
- updated_at
- deleted_at
```

---

## 画面遷移

### ファーストステップ

```
ルート -> 新規登録画面 -> 掲示板表示画面
      -> ログイン画面 -> 掲示板表示画面
                    -> メンバー個人画面 -> 編集画面
                                     -> 削除確認画面 -> 削除完了画面
```


---

## スケジュール

- ~11/13 ... 画面遷移設計完了 100%
- ~11/16 ... メンバー登録アプリ作成完了 80%
  - 残タスク
    - 重複処理
- ~11/18 ... 掲示板アプリ画面遷移設計完了 0%
- ~11/21 ... 掲示板アプリ作成完了 0%
- ~11/23 ... 管理画面遷移設計完了 0%
- ~11/27 ... 管理画面追加完了 0%
- ~11/30 ... テスト完了 0%
- ~12/4 ... デプロイ 0%