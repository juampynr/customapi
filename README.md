This sample module defines a couple routes that outline how to create your
own API endpoints.

It defines a couple routes:

/api/v1/node/{node}: returns a node using the default serializer to return
an XML response.

/api/v1/node-custom/{node}: plays around with the Serializer API to
build a custom structure that is then encoded to XML.
