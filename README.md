This sample module defines a couple routes that outline how to create your
own API endpoints.

It defines a couple routes:

/api/v1/node/{node}: returns a node using the default serializer to return
an XML response. [Here is a sample response.](https://gist.github.com/juampynr/799eec8512a871ed0c59ce33ce30ad37)

/api/v1/node-custom/{node}: plays around with the Serializer API to
build a custom structure that is then encoded to XML. [Here is a sample response](https://gist.github.com/juampynr/794a0d7938d566909fafda38b7cbe661)
