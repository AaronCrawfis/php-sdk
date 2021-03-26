<?php

/**
 * This file is automatically generated by `composer build-grpc`
 */

namespace Dapr\Client\Interfaces\V1;

/**
 * Type of HTTP 1.1 Methods
 * RFC 7231: https://tools.ietf.org/html/rfc7231#page-24
 *
 * Protobuf type <code>dapr.proto.common.v1.HTTPExtension.Verb</code>
 */
interface VerbInterface
{
	/** Generated from protobuf enum <code>NONE = 0;</code> */
	public const NONE = 0;

	/** Generated from protobuf enum <code>GET = 1;</code> */
	public const GET = 1;

	/** Generated from protobuf enum <code>HEAD = 2;</code> */
	public const HEAD = 2;

	/** Generated from protobuf enum <code>POST = 3;</code> */
	public const POST = 3;

	/** Generated from protobuf enum <code>PUT = 4;</code> */
	public const PUT = 4;

	/** Generated from protobuf enum <code>DELETE = 5;</code> */
	public const DELETE = 5;

	/** Generated from protobuf enum <code>CONNECT = 6;</code> */
	public const CONNECT = 6;

	/** Generated from protobuf enum <code>OPTIONS = 7;</code> */
	public const OPTIONS = 7;

	/** Generated from protobuf enum <code>TRACE = 8;</code> */
	public const TRACE = 8;

	public static function name($value);


	public static function value($name);
}