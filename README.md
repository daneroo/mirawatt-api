# Mirawatt API

This project descibes mirawatt's data interchange formats, and API.

The pupose of this repository is to evolve this standard in a collborative manner.

Mirawatt's purpose is to gather, transport, and present energy consumtion and usgae information.
The primary application is to gather electrical consumption from in-home sensors,
and to present the gathered data, to consummers through the web, typically on a mobile platform.

The intended application is to gather measurement at the approximate grain of a second, and typical aggregated views of the consumption data at the _minute_, _hour_, _day_, and _month_ levels.

There may be different usage scenarios which evolve over time, such as historical archival or analysis, which have been taken into account in the design of the platform.

## Terms
*   __sensor__: a device which gather point-in-time cunsumption measurements. This could be the measurement of whole-house energy consumption, or measurment of individual cirucuit level energy consumption.
*   __account__: sensors may be grouped at a first level into __accounts__ to present an aggreggated view of consumption. This would typically be a _home_.
*   __scope__: measurments often need to be presented and or aggregated at different time scales; we refer to those time scales as _scopes_.

# Format
Systems such as Mirawatt, benefit greatly from standards adoption, and reusable components.
Because of its pervasive adotoption as well as its flexibility and it simplicity, the chosen format is __JSON__ (_Javascript Object Notation_).

## JSON
All data will be transported as [JSON](http://www.json.org/). This widely adopted format is well documented, and being a proper subset of the Javascript language, is natively supported in all browsers, and is a well supported format in all languages ([Javascript,Python,Perl,Java,C,Objective-C,C++,C#, and almost any language you can think of](http://www.json.org/)).

## Identifiers
Identifiers as __accountId__, and __sensorId__ are repersented as strings, even where numerical in nature.

## Dates and Times
All date and time values will be expressed as strings in ISO-8601 format (including UTC timezone). e.g.:

    "2011-05-06T05:12:29Z"
## Measurement values
All measured values are to be represented as JSON Numbers. The implied units will be watts (__W__), (or watt-hours (__Wh__),  where appropriate) unless otherwise specified. Where no data is available a _null_ value may be used.

This is a floating poit representaion. In Javascript this is backed by the IEEE 754 Standard (with a 52-bit mantissa and an 11-bit exponent). In practice it may be appropriate to quantize these values (to integers for example, for considerations of transport size or run-legth compression for archival).

Where multiple sensor data is aggregated into a single account, bothe the `sensorId`, and the `obs.v` attributes are represented as arrays. `v:123` becomes `v:[123,456]`, and `sensorId:"s1"` becomes `sensorId:["s2","s3"]`.

Where only one sensor is implied (single sensor home), the `sensorId` may be omitted.

## Named scopes

* __"Live", scopeId:0__: Samples are typically at the 1s frequency
* __"Hour", scopeId:1__: Samples every minute.
* __"Day", scopeId:2__: Samples every Hour
* __"Month", scopeId:3__: Samples every `day`
* __"Year", scopeId:4__: Samples every `month`

## Higher Level Scopes
For the the __Month__ and __Year__ scopes, where samples are respectively at the `day`, and `month`, discretion is left to the application as to where these boundaries lie: typically related to the timezone of the sensor locale. These boundaries may also be sensitive to Daylight savins considerations.
  

## Full examples:

<center>__Single Scope, Single Sensor__</center>

    { "version":"1.0",
      "accountId":"daniel.lauzon@mirawatt.com",
      "feeds": [ {
        "sensorId":"main-panel",
        "scopeId":0, "name":"Live",
        "obs":[
           {"t":"2011-05-06T05:12:26Z","v":940},
           {"t":"2011-05-06T05:11:40Z","v":935},
           {"t":"2011-05-06T05:11:30Z","v":934},
           {"t":"2011-05-06T05:11:20Z","v":936},
           {"t":"2011-05-06T05:11:10Z","v":932},
           {"t":"2011-05-06T05:11:00Z","v":935},
           {"t":"2011-05-06T05:10:50Z","v":932},
           {"t":"2011-05-06T05:10:40Z","v":931},
           {"t":"2011-05-06T05:10:30Z","v":932}
        ]
      }
    ]}

<center>__Single Scope, Multiple Sensor__</center>

    { "version":"1.0",
      "accountId":"001DC9103971",
      "feeds": [ {
        "sensorId":["112203081766779e","1122030817667789","11220308176677a2","1122030815667742"],
        "scopeId":0, "name":"Live",
        "obs":[
           {"t":"2011-05-06T05:12:26Z","v":[940, 840, 740, 640]},
           {"t":"2011-05-06T05:11:40Z","v":[935, 835, 735, 635]},
           {"t":"2011-05-06T05:11:30Z","v":[934, 834, 734, 634]},
           {"t":"2011-05-06T05:11:20Z","v":[936, 836, 736, 636]},
           {"t":"2011-05-06T05:11:10Z","v":[932, 832, 732, 632]},
           {"t":"2011-05-06T05:11:00Z","v":[935, 835, 735, 635]},
           {"t":"2011-05-06T05:10:50Z","v":[932, 832, 732, 632]},
           {"t":"2011-05-06T05:10:40Z","v":[931, 831, 731, 631]},
           {"t":"2011-05-06T05:10:30Z","v":[932, 832, 732, 632]}
        ]
      }
    ]}

<center>__Multiple Scope, Single Sensor__</center>

    { "version":"1.0",
      "accountId":"daniel.lauzon@mirawatt.com",
      "feeds": [ {
        "scopeId":"1", "name":"Hour",
        "t":"2011-05-06T05:12:00Z", "v":648,
        "obs":[
           {"t":"2011-05-06T05:12:00Z","v":933},
           {"t":"2011-05-06T05:11:00Z","v":934},
           {"t":"2011-05-06T05:10:00Z","v":934},
           {"t":"2011-05-06T05:09:00Z","v":933},
           {"t":"2011-05-06T05:08:00Z","v":937},
           {"t":"2011-05-06T05:07:00Z","v":943},
           {"t":"2011-05-06T05:06:00Z","v":940},
           {"t":"2011-05-06T05:05:00Z","v":909}
        ]
      }, {
        "scopeId":"2", "name":"Day",
        "t":"2011-05-06T05:00:00Z", "v":648,
        "obs":[
           {"t":"2011-05-06T05:00:00Z","v":933},
           {"t":"2011-05-06T06:00:00Z","v":934},
           {"t":"2011-05-06T07:00:00Z","v":934},
           {"t":"2011-05-06T08:00:00Z","v":933},
           {"t":"2011-05-06T08:00:00Z","v":937},
           {"t":"2011-05-06T10:00:00Z","v":943},
           {"t":"2011-05-06T11:00:00Z","v":940},
           {"t":"2011-05-06T12:00:00Z","v":909}
        ]
      }
    ]}


## XML 
There was a pre-existing XML format, which has been deprecated.

# Transport

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

## Authentication, Authorization and Ecryption.
This topic has not yet been addressed fully.

*   Token based service authentication
*   Encryption should be implements as ssl/tls, not our implementation

# Semantics

## Multiple Channels
Produire un fragment JSON. valeurs multiples en [1,2,3,4,5,6,7,8]


TODO; encryption