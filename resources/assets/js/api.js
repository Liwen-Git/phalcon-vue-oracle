import axios from 'axios';
import {Message} from "element-ui";

window.baseApiUrl = window.baseApiUrl || '';
const CODE_OK = 0;
const CODE_UN_LOGIN = 10003;

class ResponseError {
    constructor(response) {
        this.response = response;
    }
}

/**
 * 处理 result
 * @param res
 * @returns {Promise<never>|*}
 */
function handleRes(res) {
    if (res && res.code === CODE_OK) {
        return res.data;
    } else {
        return Promise.reject(new ResponseError(res));
    }
}

/**
 * 处理 error
 * @param error
 */
function handleError(error) {
    if (error instanceof ResponseError) {
        let res = error.response;
        if (res && res.code) {
            switch (res.code) {
                case CODE_UN_LOGIN:
                    window.location = res.data.login_url;
                    Message.error('您的登录信息已失效，请重新登录');
                    break;
                default:
                    console.log('接口返回错误信息:', res);
                    break;
            }
        } else {
            console.log('未知错误:', res);
        }
    } else {
        console.error('network error', error);
        Message.error('请求超时，请检查网络');
    }
}

/**
 * 拼接真实的url
 * @param url
 * @returns {string|*}
 */
function getRealUrl(url) {
    if (url.indexOf(window.baseApiUrl) === 0) {
        return url;
    }
    if (url.indexOf('/') === 0) {
        url = url.substr(1);
    }
    return window.baseApiUrl + url;
}

/**
 * axios get封装
 * @param url
 * @param params
 * @param defaultHandlerRes
 * @returns {Promise<AxiosResponse<any> | never>}
 */
function get(url, params, defaultHandlerRes = true) {
    let options = {
        header: {'X-Requested-With': 'XMLHttpRequest'},
        params: params,
    };
    url = getRealUrl(url);
    let promise = axios.get(url, options).then(res => {
        let result = res.data;
        if (defaultHandlerRes) {
            return handleRes(result);
        } else {
            return result;
        }
    });
    promise.catch(handleError);
    return promise;
}

/**
 * axios post封装
 * @param url
 * @param params
 * @param defaultHandlerRes
 * @returns {Promise<AxiosResponse<any> | never>}
 */
function post(url, params, defaultHandlerRes = true) {
    let options = {
        header: {'X-Requested-With': 'XMLHttpRequest'},
        timeout: 1000 * 30,
    };
    url = getRealUrl(url);
    let promise = axios.post(url, params, options).then(res => {
        let result = res.data;
        if (defaultHandlerRes) {
            return handleRes(result);
        } else {
            return result;
        }
    });
    promise.catch(handleError);
    return promise;
}

/**
 * 导出方法，供全局使用
 */
export default {
    get,
    post,
    handleRes,
}
