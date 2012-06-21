# Mirawatt API

This project describes mirawatt's data interchange formats, and API.

For a disussion on [User Experience](UXDesign.md) (GUI) see [this document]((UXDesign.md)

The purpose of this repository is to evolve this standard in a collaborative manner.

Mirawatt's purpose is to gather, transport, and present energy consumption and usage information.
The primary application is to gather electrical consumption from in-home sensors,
and to present the gathered data, to consummers through the web, typically on a mobile platform.

The intended application is to gather measurements at the approximate grain of a second, and typical aggregated views of the consumption data at the _minute_, _hour_, _day_, and _month_ levels.

There may be different usage scenarios which evolve over time, such as historical archival or analysis, which have been taken into account in the design of the platform.

### Definitions
* __sensor__: a device which gathers point-in-time cunsumption measurements. This could be the measurement of whole-house energy consumption, or measurement of individual circuit level energy consumption.
* __account__: sensors may be grouped at a first level into __accounts__ to present an aggregated view of consumption. This would typically be a _home_ or _dwelling_.
* __scope__: measurements often need to be presented and or aggregated at different time scales; we refer to those time scales as _scopes_. (_hour_,_day_,_month_,_year_)
* __hub (or DAT)__: the component which aggregates the __sensor__ data for transmission with the mirawatt server or other clients.


## Format
Systems such as Mirawatt, benefit greatly from standards adoption, and reusable components.
Because of its pervasive adoption as well as its flexibility and it simplicity, the chosen format is __JSON__ (_Javascript Object Notation_).

### JSON
All data will be transported as [JSON](http://www.json.org/). This widely adopted format is well documented, and being a proper subset of the Javascript language, is natively supported in all browsers, and is a well supported format in most languages ([Javascript,Python,Perl,Java,C,Objective-C,C++,C#, and almost any language you can think of](http://www.json.org/)).

### Identifiers
Identifiers such as __accountId__, and __sensorId__ are reperesented as strings, even where they are numerical in nature.

### Dates and Times
All date and time values will be expressed as strings in ISO-8601 format (including UTC timezone). e.g.:

    "2011-05-06T05:12:29Z"
    
This format may _optionally_ contain a fractional second component i.e. `"2012-04-23T22:32:12.3456Z"`.

### Measurement values
All measured values are to be represented as JSON Numbers. The implied units will be watts (__W__), (or watt-hours (__Wh__),  where appropriate) unless otherwise specified. Where no data is available a _null_ value may be used.

This is a floating point representation. In Javascript this is backed by the IEEE 754 Standard (with a 52-bit mantissa and an 11-bit exponent). In practice it may be appropriate to quantize these values (to integers for example, for considerations of transport size or run-legth compression for archival).

Where multiple sensor data is aggregated into a single account, both the the `sensorId`, and the `obs.v` attributes are represented as arrays. `v:123` becomes `v:[123,456]`, and `sensorId:"s1"` becomes `sensorId:["s2","s3"]`.

Where only one sensor is implied (single sensor home), the `sensorId` may be omitted.

### Named scopes

* __"Live", scopeId:0__: Samples are typically at the 1 `second` frequency, or better
* __"Hour", scopeId:1__: Samples every `minute`.
* __"Day", scopeId:2__: Samples every `hour`
* __"Month", scopeId:3__: Samples every `day`
* __"Year", scopeId:4__: Samples every `month`

### Higher Level Scopes
For the the __Month__ and __Year__ scopes, where samples are respectively at the `day`, and `month`, discretion is left to the application as to where these boundaries lie: typically related to the timezone of the sensor locale. These boundaries may also be sensitive to daylight savings considerations. This is one reason for which the timestamps are presented at each sample.

### Discussion of format choices
As seen in the examples below, some choices are implicit: the first is that a set of sensor's data is always meant to be handled as a single time-coincident set of values. This choice was made to greatly simplify the temporal __lining-up__ of the samples. which is best handled at a single point, preferably closest to where the samples are taken.

There is an assumption that all clocks are accurate.

The format is self-consistent and independant of other transport/storage considerations, such as url-endpoint parameters, although those may be used for other considerations such as authentication, or caching policies.

The representation of the timestamp format, although may seem to be redundant, actually has lower entropy and gives better performance in http transport when properly using `Content-Encoding: gzip` or `deflate` headers and encoding.
Given  also the considerations above for day/month boundaries, this seems like the simplest choice.

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

# RPC Transport
There are three flavors of our api, with respect to service invocation:

* __Straight POST/GET__: simplest form
* __JSON-RPC__: we use the version 2 spec. Has better error handling semantics (application level). [json-rpc spec](http://json-rpc.org/wiki/specification)
* __Dnode__: based on socket-io, for bi-directional communications, with transport fallback from web-sockets, to flash-sockets, html streaming-response to xhr-polling, and finally jsonp-polling
* __REST__: REST based semantics are still being investigated, but their main advantage will be for the archival/retreival stage.

## Implementation phases

### Initial Phase

* __set/get__: These are the only two actions defined for the initial operational deployment, their intended bodies are exactly described by the format above. The role of the endpoint is essentially to reflect the last given value for a given `accountId/scopeId`.

It is important to note, that the hub is therefore responsible for both aggregation of data for the different __scopes__, and any long term storage/archival requirements.

### Service Endpoints
The endpoint may allow x-posting by implementing __INFO__ method.

* __POST__: the url endpoint is `http://`_mirawatt-server_`/incoming`, currently `http://mirawatt.cloudfoundry.com/incoming`.
* __JSON-RPC__: the url endpoint is `http://`_mirawatt-server_`/jsonrpc`, currently `http://mirawatt.cloudfoundry.com/jsonrpc`.

### Later phases
* __update__: This action is meant to transfer partial, or updated information, the precise semantics of the implied aggregation are under developpment. 

* __on-demand__: in this phase, an enhanced hub, would push data only when requested, this requires bi-directional communication, to implement a pub-sub behaviour, where essentially the __hub__ is the subscriber to presence events of eventual consumer clients. That is, the __hub__ only pushes data when requested, for example when a client is viewing live data.

* __archival/retrieval__: This phase would fully implement long-term persistence on the platform. Initial implementation of the storage backend is under experiment. The implementation uses entropy coding of the stored data in an effective manner for subsequent retrieval, transport and processing using an incremental map-reduce. (based on CouchDB)

## Authentication, Authorization and Ecryption.
This topic has not yet been addressed fully.

*   Token based service authentication
*   Encryption should be implements as ssl/tls, not our implementation


