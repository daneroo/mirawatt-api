# Mirawatt API

This project descibes and implements mirawatt's
data interchange formats, and API.

# Transport & Format
## URL service endpoints
We will use mostly REST based services
`http://api.mirawatt.com/svc/`__entity__/`params-such-as-id`,  
and where __entity__ is one of

*   __time__: Produce a timestamp for device clock setup
*   __state__: GET and POST methods
*   __account__
*   __history__:
*   __sync__
*   __token__:

The endpoint may allow x-posting by implementing __INFO__ method.

## JSON
All data will be transported as JSON.
Versioned, json-schema validated.

## Dates and Times
All date and time values will be express in ISO-8601 formats (including timezone)

## XML 
There was a pre-existing XML format, which has been deprecated.

# Semantics
## Multiple Channels
Produire un fragment JSON. valeurs multiples en [1,2,3,4,5,6,7,8]


TODO; encryption