//
//  toolIosSize.m
//  trip
//
//  Created by 沈 吾苓 on 13-2-6.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import "toolIosSize.h"



@implementation toolIosSize

+ (CGFloat)heightAll
{
    CGRect r = [ UIScreen mainScreen ].applicationFrame;
    return r.size.height+iosSizeStatusBarHeight;
}
+ (CGFloat)heightWithoutStatusBar
{
    CGRect r = [ UIScreen mainScreen ].applicationFrame;
    return r.size.height;
}
+ (CGFloat)heightWithoutNav
{
    CGRect r = [ UIScreen mainScreen ].applicationFrame;
    return r.size.height - iosSizeNavHeight;
}
+ (CGFloat)heightWithoutTab
{
    CGRect r = [ UIScreen mainScreen ].applicationFrame;
    return r.size.height - iosSizeTabHeight;
}
+ (CGFloat)heightWithoutNavAndTab
{
    CGRect r = [ UIScreen mainScreen ].applicationFrame;
    return r.size.height - iosSizeTabHeight - iosSizeNavHeight;
}
+ (CGFloat)heightWithoutToolBar
{
    CGRect r = [ UIScreen mainScreen ].applicationFrame;
    return r.size.height - iosSizeToolHeight;
}
+ (CGFloat)widthScreen
{
    CGRect r = [ UIScreen mainScreen ].applicationFrame;
    return r.size.width;
}

@end
