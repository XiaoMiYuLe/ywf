<?php
define('SOAR_ERR_OK', '0000');

define('SOAR_ERR_COMM_FAIL',            '9000');    # 与服务节点通信错误
define('SOAR_ERR_CALL_TIMEOUT',         '9001');    # 调用超时
define('SOAR_ERR_RESOURCE',             '9002');    # 内部资源错误
define('SOAR_ERR_SVC_NOT_FOUND',        '9003');    # 调用不存在服务
define('SOAR_ERR_DB',                   '9004');    # 数据库错误
define('SOAR_ERR_MSG',                  '9005');    # 消息格式错误
define('SOAR_ERR_PARA',                 '9006');    # 参数错误
define('SOAR_ERR_NODE_NOT_FOUND',       '9007');    # 调用不存在节点
define('SOAR_ERR_SVC_INVALID',          '9008');    # 无效服务
define('SOAR_ERR_SVC_DUPLICATE',        '9009');    # 服务重复
define('SOAR_ERR_CMD_INVALID',          '9010');    # 操作码不存在
define('SOAR_ERR_SVR_NOT_FOUND',        '9011');    # 输入消息越界
define('SOAR_ERR_FILE',                 '9012');    # 操作文件失败

define('SOAR_ERR_SESSION_EXIST',        '8200');    # 会话已存在
define('SOAR_ERR_SESSION_NOT_FOUND',    '8201');    # 会话未找到
define('SOAR_ERR_SESSION_CREATE_FAIL',  '8202');    # 会话创建失败
define('SOAR_ERR_SESSION_SID_INVALID',  '8203');    # 会话标号无效
define('SOAR_ERR_SESSION_KEY_NOT_FOUND','8204');    # 键未找到
define('SOAR_ERR_SESSION_PUTKEY_FAIL',  '8205');    # 设置键出错
define('SOAR_ERR_SESSION_VAL_NOT_INT',  '8206');    # 值非整型
define('SOAR_ERR_SESSION_MODKEY_FAIL',  '8207');    # 修改键出错
define('SOAR_ERR_SESSION_SID_DISCORD',  '8208');    # 会话内容不一致
define('SOAR_ERR_SESSION_KEY_FULL',     '8209');    # 键已满

define('SOAR_ERR_SECURE_NOKEY',         '8300');    # 密钥不存在
define('SOAR_ERR_SECURE_ENCRYPT',       '8301');    # 加密出错
define('SOAR_ERR_SECURE_DECRYPT',       '8302');    # 解密出错
define('SOAR_ERR_SECURE_SIGN',          '8303');    # 签名出错
define('SOAR_ERR_SECURE_VERIFY',        '8304');    # 校验出错
define('SOAR_ERR_SECURE_PASSWD_DISCORD','8305');    # 口令不一致

define('SOAR_ERR_MAILFWD_BLACK',        '8400');    # 黑名单

define('SOAR_ERR_CONFIRM_SEQ_INVALID',  '8500');    # 序列号无效
define('SOAR_ERR_CONFIRM_SEQ_NOT_FOUND','8501');    # 序列号未找到
define('SOAR_ERR_CONFIRM_ID_NOT_MATCH', '8502');    # ID未匹配
define('SOAR_ERR_CONFIRM_CD_NOT_MATCH', '8503');    # 验证码未匹配
?>
