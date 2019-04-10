const nJwt = require('njwt');

export function generate(apiKey, apiSecret)
{
  let payload = {
    iss: apiKey,
    exp: ((new Date()).getTime() + 6000000)
  }

  let jwt = nJwt.create(payload,apiSecret,"HS256");
  return jwt.compact()
}