//
//  toolTime.h
//  trip
//
//  Created by 沈 吾苓 on 13-2-19.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface toolTime : NSObject

+ (NSDate *)int2nsdate:(int)timestamp;
+ (NSString *)int2Ymd:(int)timestamp;
+ (NSDate *)str2nsdate:(NSString *)str;
@end
