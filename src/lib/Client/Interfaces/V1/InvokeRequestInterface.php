<?php

/**
 * This file is automatically generated by `composer build-grpc`
 */

namespace Dapr\Client\Interfaces\V1;

/**
 * InvokeRequest is the message to invoke a method with the data.
 * This message is used in InvokeService of Dapr gRPC Service and OnInvoke
 * of AppCallback gRPC service.
 *
 * Generated from protobuf message <code>dapr.proto.common.v1.InvokeRequest</code>
 */
interface InvokeRequestInterface
{
	/**
	 * Required. method is a method name which will be invoked by caller.
	 *
	 * Generated from protobuf field <code>string method = 1;</code>
	 * @return string
	 */
	public function getMethod();


	/**
	 * Required. method is a method name which will be invoked by caller.
	 *
	 * Generated from protobuf field <code>string method = 1;</code>
	 * @param string $var
	 * @return $this
	 */
	public function setMethod($var);


	/**
	 * Required. Bytes value or Protobuf message which caller sent.
	 * Dapr treats Any.value as bytes type if Any.type_url is unset.
	 *
	 * Generated from protobuf field <code>.google.protobuf.Any data = 2;</code>
	 * @return \Google\Protobuf\Any|null
	 */
	public function getData();


	public function hasData();


	public function clearData();


	/**
	 * Required. Bytes value or Protobuf message which caller sent.
	 * Dapr treats Any.value as bytes type if Any.type_url is unset.
	 *
	 * Generated from protobuf field <code>.google.protobuf.Any data = 2;</code>
	 * @param \Google\Protobuf\Any $var
	 * @return $this
	 */
	public function setData($var);


	/**
	 * The type of data content.
	 * This field is required if data delivers http request body
	 * Otherwise, this is optional.
	 *
	 * Generated from protobuf field <code>string content_type = 3;</code>
	 * @return string
	 */
	public function getContentType();


	/**
	 * The type of data content.
	 * This field is required if data delivers http request body
	 * Otherwise, this is optional.
	 *
	 * Generated from protobuf field <code>string content_type = 3;</code>
	 * @param string $var
	 * @return $this
	 */
	public function setContentType($var);


	/**
	 * HTTP specific fields if request conveys http-compatible request.
	 * This field is required for http-compatible request. Otherwise,
	 * this field is optional.
	 *
	 * Generated from protobuf field <code>.dapr.proto.common.v1.HTTPExtension http_extension = 4;</code>
	 * @return \Dapr\Proto\Common\V1\HTTPExtension|null
	 */
	public function getHttpExtension();


	public function hasHttpExtension();


	public function clearHttpExtension();


	/**
	 * HTTP specific fields if request conveys http-compatible request.
	 * This field is required for http-compatible request. Otherwise,
	 * this field is optional.
	 *
	 * Generated from protobuf field <code>.dapr.proto.common.v1.HTTPExtension http_extension = 4;</code>
	 * @param \Dapr\Proto\Common\V1\HTTPExtension $var
	 * @return $this
	 */
	public function setHttpExtension($var);
}