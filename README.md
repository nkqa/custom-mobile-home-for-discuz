# custom-mobile-home-for-discuz

Discuz! X3.5 手机版首页自定义插件（插件 ID：`neko_mobile_home`）

Discuz! 原生不支持单独为手机版设置首页。本插件允许站长在后台为手机访问者单独指定首页入口，桌面版完全不受影响。

作者：MCNeko ([www.mcneko.com](https://www.mcneko.com))

## 功能

在后台插件设置中为手机版选择首页，可选：

| 选项 | 目标地址 |
|------|----------|
| 门户 | `portal.php` |
| 论坛 | 保留原生手机版论坛首页（默认） |
| 圈子 | `group.php` |
| 导读 | `forum.php?mod=guide&view=newthread` |
| 日志 | `home.php?mod=space&do=blog&view=all` |
| 相册 | `home.php?mod=space&do=album&view=all` |
| 记录 | `home.php?mod=space&do=doing&view=all` |
| 排行榜 | `misc.php?mod=ranklist` |
| 动态 | `home.php?mod=space&do=home&view=all` |
| 帮助 | `misc.php?mod=faq` |
| 自定义 | 自行填写站内相对链接或完整 `https://` 链接 |

## 安装

1. 将本目录上传至论坛 `source/plugin/neko_mobile_home/`；
2. 进入后台「应用 → 插件」，安装「手机版首页自定义」并启用；
3. 在插件设置中选择手机版首页，保存。

卸载：后台停用并卸载插件，删除 `source/plugin/neko_mobile_home/` 目录即可。本插件不建表、不写任何核心文件。

## 实现说明

- 插件通过手机版全局钩子（`mobileplugin_neko_mobile_home::common()`）重定向，不修改任何 Discuz 核心代码；
- 仅在手机版访问站点首页入口 `index.php`（含站点根路径 `/`）时生效：点击论坛、门户等导航链接（`forum.php`、`portal.php`）时不会被拦截，正常显示对应页面；
- 桌面版及手机版的其他页面（版块页、主题列表页等）均不受影响；
- 已内置防死循环保护：自定义链接指回 `index.php` 时不再跳转。

## 兼容性

- Discuz! X3.5（PHP 7.x / 8.x）
- 需启用官方「掌上论坛」（mobile）插件的触屏版功能

## 许可

以 MIT 许可发布，可自由修改与分发，请保留作者信息。
