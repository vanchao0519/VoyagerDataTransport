<?php


namespace VoyagerDataTransport\Contracts;

interface IRouteParameters
{
    const URL = 'url';
    const CONTROLLER = 'controllerName';
    const ACTION = 'actionName';
    const ALIAS = 'alias';
    const GET = 'get';
    const POST = 'post';
}