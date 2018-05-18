<?php
class SOAR_ERR
{/*{{{*/
    const OK=                   '0000';

    const COMM_FAIL=            '9000'; // 与服务节点通信错误
    const CALL_TIMEOUT=         '9001'; // 调用超时
    const RESOURCE=             '9002'; // 内部资源错误
    const SVC_NOT_FOUND=        '9003'; // 调用不存在服务
    const DB=                   '9004'; // 数据库错误
    const MSG=                  '9005'; // 消息格式错误
    const PARA=                 '9006'; // 参数错误
    const NODE_NOT_FOUND=       '9007'; // 调用不存在节点
    const SVC_INVALID=          '9008'; // 无效服务
    const SVC_DUPLICATE=        '9009'; // 服务重复
    const CMD_INVALID=          '9010'; // 操作码不存在
    const SVR_NOT_FOUND=        '9011'; // 输入消息越界
    const FILE_ACCESS=          '9012'; // 操作文件失败

    const SESSION_EXIST=        '8200'; // 会话已存在
    const SESSION_NOT_FOUND=    '8201'; // 会话未找到
    const SESSION_CREATE_FAIL=  '8202'; // 会话创建失败
    const SESSION_SID_INVALID=  '8203'; // 会话标号无效
    const SESSION_KEY_NOT_FOUND='8204'; // 键未找到
    const SESSION_PUTKEY_FAIL=  '8205'; // 设置键出错
    const SESSION_VAL_NOT_INT=  '8206'; // 值非整型
    const SESSION_MODKEY_FAIL=  '8207'; // 修改键出错
    const SESSION_SID_DISCORD=  '8208'; // 会话内容不一致
    const SESSION_KEY_FULL=     '8209'; // 键已满
    const SESSION_ENCRYPT=      '8210'; // 加密出错
    const SESSION_DECRYPT=      '8211'; // 解密出错

    const SECURE_NOKEY=         '8300'; // 密钥不存在
    const SECURE_ENCRYPT=       '8301'; // 加密出错
    const SECURE_DECRYPT=       '8302'; // 解密出错
    const SECURE_SIGN=          '8303'; // 签名出错
    const SECURE_VERIFY=        '8304'; // 校验出错
    const SECURE_PASSWD_DISCORD='8305'; // 口令不一致
    const SOAR_ERR_SECURE_ENCODE='8306';// 编码出错
    const SOAR_ERR_SECURE_DECODE='8307';// 解码出错

    const MAILFWD_BLACK=        '8400'; // 黑名单

    const CONFIRM_SEQ_INVALID=  '8500'; // 序列号无效
    const CONFIRM_SEQ_NOT_FOUND='8501'; // 序列号未找到
    const CONFIRM_ID_NOT_MATCH= '8502'; // ID未匹配
    const CONFIRM_CD_NOT_MATCH= '8503'; // 验证码未匹配
}/*}}}*/

class MYSQL_ERR
{/*{{{*/
    const UNIQUE_VIOLATE=       1062;
}/*}}}*/
?>
