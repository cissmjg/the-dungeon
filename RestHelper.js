import { STARTING_URL } from "./env.js";

export function buildURL(endpoint) {
    let final_rest_uri = endpoint;
    if (!endpoint.endsWith(".php")) {
        final_rest_uri += ".php";
    }

    return STARTING_URL + final_rest_uri;
}

export function addParameter(url, paramName, paramValue) {
    if (url.includes("?")) {
        url += "&";
    } else {
        url += "?";
    }

    let encodedParamName = encodeURIComponent(paramName);
    let encodedParamValue = encodeURIComponent(paramValue);

    return url + encodedParamName + "=" + encodedParamValue;
}

export function buildCharacterActionRouterURL(characterAction) {
    let url = buildURL('characterActionRouter');
    return addParameter(url, 'characterAction', characterAction)
}
