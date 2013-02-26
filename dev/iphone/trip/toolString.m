//
//  toolString.m
//  trip
//
//  Created by 沈 吾苓 on 13-2-18.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import "toolString.h"

@implementation toolString

+ (int)strWidth:(NSString*)strtemp

{
    NSStringEncoding enc = CFStringConvertEncodingToNSStringEncoding(kCFStringEncodingGB_18030_2000);
    NSData* da = [strtemp dataUsingEncoding:enc];
    return [da length];
}

@end
